<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities\Fetch;

use DOMElement;
use DOMXPath;

/**
 * Parses Ubuntu OVAL XML files and returns structured vulnerability data.
 */
class ParseOvalXml
{
    public function parse(string $filePath, int $daysOnly = 0): array
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File '{$filePath}' not found.");
        }

        $cutoffDate = null;
        if ($daysOnly > 0) {
            $cutoffDate = new \DateTime();
            $cutoffDate->modify("-{$daysOnly} days");
            $cutoffDate->setTime(0, 0, 0);
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        if (!$dom->load($filePath)) {
            $errors = libxml_get_errors();
            $msgs = [];
            foreach ($errors as $err) {
                $msgs[] = "Line {$err->line}: {$err->message}";
            }
            libxml_clear_errors();
            throw new \RuntimeException('Error loading XML: ' . implode("\n", $msgs));
        }
        libxml_clear_errors();

        $xpath = $this->createXpath($dom);

        $testMap = $this->buildTestMap($xpath);
        $objVarMap = $this->buildObjVarMap($xpath);
        $varPackageMap = $this->buildVarPackageMap($xpath);
        $stateVersionMap = $this->buildStateVersionMap($xpath);

        $vulns = [];

        /** @var DOMElement $defNode */
        foreach ($xpath->query('//def:definition') as $defNode) {
            if ($defNode->getAttribute('class') !== 'patch') {
                continue;
            }

            $vuln = $this->extractDefinition($xpath, $defNode, $cutoffDate, $testMap, $objVarMap, $varPackageMap, $stateVersionMap);
            if ($vuln === null) {
                continue;
            }

            $vulns[] = $vuln;
        }

        usort($vulns, fn ($a, $b) => strcmp($b['issued_date'], $a['issued_date']));

        return $vulns;
    }

    private function createXpath(\DOMDocument $dom): DOMXPath
    {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('oval', 'http://oval.mitre.org/XMLSchema/oval-common-5');
        $xpath->registerNamespace('def',  'http://oval.mitre.org/XMLSchema/oval-definitions-5');
        $xpath->registerNamespace('ind',  'http://oval.mitre.org/XMLSchema/oval-definitions-5#independent');
        $xpath->registerNamespace('unix', 'http://oval.mitre.org/XMLSchema/oval-definitions-5#unix');
        $xpath->registerNamespace('linux','http://oval.mitre.org/XMLSchema/oval-definitions-5#linux');
        return $xpath;
    }

    private function buildTestMap(DOMXPath $xpath): array
    {
        $map = [];
        /** @var DOMElement $node */
        foreach ($xpath->query('//linux:dpkginfo_test') as $node) {
            $id = $node->getAttribute('id');
            $ns = 'http://oval.mitre.org/XMLSchema/oval-definitions-5#linux';
            $stateEl = $node->getElementsByTagNameNS($ns, 'state')->item(0);
            $objEl   = $node->getElementsByTagNameNS($ns, 'object')->item(0);

            $map[$id] = [
                'state_ref' => $stateEl ? $stateEl->getAttribute('state_ref') : '',
                'obj_ref'   => $objEl   ? $objEl->getAttribute('object_ref') : '',
            ];
        }
        return $map;
    }

    private function buildObjVarMap(DOMXPath $xpath): array
    {
        $map = [];
        /** @var DOMElement $node */
        foreach ($xpath->query('//linux:dpkginfo_object') as $node) {
            $id = $node->getAttribute('id');
            $nameEl = $node->getElementsByTagNameNS(
                'http://oval.mitre.org/XMLSchema/oval-definitions-5#linux', 'name'
            )->item(0);
            if ($nameEl) {
                $varRef = $nameEl->getAttribute('var_ref');
                if ($varRef !== '') {
                    $map[$id] = $varRef;
                }
            }
        }
        return $map;
    }

    private function buildVarPackageMap(DOMXPath $xpath): array
    {
        $map = [];
        /** @var DOMElement $node */
        foreach ($xpath->query('//def:constant_variable[@datatype="string"]') as $node) {
            $id = $node->getAttribute('id');
            $values = [];
            // <value> elements inside constant_variable are in the default definitions namespace,
            // not the oval-common namespace as one might expect
            foreach ($node->getElementsByTagNameNS(
                'http://oval.mitre.org/XMLSchema/oval-definitions-5', 'value'
            ) as $el) {
                $values[] = trim($el->textContent);
            }

            if (!empty($values)) {
                $map[$id] = array_unique($values);
            }
        }
        return $map;
    }

    private function buildStateVersionMap(DOMXPath $xpath): array
    {
        $map = [];
        /** @var DOMElement $node */
        foreach ($xpath->query('//linux:dpkginfo_state') as $node) {
            $id = $node->getAttribute('id');
            $evr = $node->getElementsByTagNameNS(
                'http://oval.mitre.org/XMLSchema/oval-definitions-5#linux', 'evr'
            )->item(0);
            if ($evr) {
                $map[$id] = trim($evr->textContent);
            }
        }
        return $map;
    }

    private function extractDefinition(
        DOMXPath $xpath,
        DOMElement $defNode,
        ?\DateTime $cutoffDate,
        array $testMap,
        array $objVarMap,
        array $varPackageMap,
        array $stateVersionMap
    ): ?array {
        $titleEl = $xpath->query('.//def:title', $defNode)->item(0);
        if (!$titleEl) {
            return null;
        }

        $severityEl = $xpath->query('.//def:severity', $defNode)->item(0);
        $severity = $severityEl ? trim($severityEl->textContent) : 'Unknown';

        $issuedNodes = $xpath->query('.//def:issued', $defNode);
        if ($issuedNodes->length === 0) {
            return null;
        }
        /** @var DOMElement $issuedEl */
        $issuedEl = $issuedNodes->item(0);
        $dateAttr = $issuedEl->getAttribute('date');
        if ($dateAttr === '') {
            return null;
        }

        $issueDate = \DateTime::createFromFormat('Y-m-d', $dateAttr);
        if (!$issueDate || ($cutoffDate && $issueDate < $cutoffDate)) {
            return null;
        }

        // USN reference
        $usnRefs = $xpath->query('.//def:reference[@source="USN"]', $defNode);
        $usnId = '';
        $usnUrl = '';
        if ($usnRefs->length > 0) {
            $usnId  = $usnRefs->item(0)->getAttribute('ref_id');
            $usnUrl = $usnRefs->item(0)->getAttribute('ref_url');
        }

        // CVEs from advisory section
        $cves = [];
        $existingCveIds = [];
        /** @var DOMElement $cveEl */
        foreach ($xpath->query('.//def:advisory/def:cve', $defNode) as $cveEl) {
            $cve = [
                'id'             => trim($cveEl->textContent),
                'priority'       => $cveEl->getAttribute('priority'),
                'cvss_score'     => $cveEl->getAttribute('cvss_score'),
                'cvss_severity'  => $cveEl->getAttribute('cvss_severity'),
                'cvss_vector'    => $cveEl->getAttribute('cvss_vector'),
                'public_date'    => $cveEl->getAttribute('public'),
            ];
            $cves[] = $cve;
            $existingCveIds[] = $cve['id'];
        }

        // CVE references from metadata
        /** @var DOMElement $refEl */
        foreach ($xpath->query('.//def:reference[@source="CVE"]', $defNode) as $refEl) {
            $refId = $refEl->getAttribute('ref_id');
            if (!in_array($refId, $existingCveIds, true)) {
                $cves[] = [
                    'id'             => $refId,
                    'priority'       => '',
                    'cvss_score'     => '',
                    'cvss_severity'  => '',
                    'cvss_vector'    => '',
                    'public_date'    => '',
                ];
            }
        }

        $descEl = $xpath->query('.//def:description', $defNode)->item(0);
        $description = $descEl ? trim($descEl->textContent) : '';

        $osInfo = $this->extractOsPlatform($xpath, $defNode);
        $packages = $this->resolvePackages($defNode, $xpath, $testMap, $objVarMap, $varPackageMap, $stateVersionMap);

        return [
            'title'             => trim($titleEl->textContent),
            'usn_id'            => $usnId,
            'usn_url'           => $usnUrl,
            'severity'          => $severity,
            'issued_date'       => $dateAttr,
            'cves'              => $cves,
            'description'       => $description,
            'affected_packages' => $packages,
            'os_name'           => $osInfo['name'] ?? '',
            'os_version'        => $osInfo['version'] ?? '',
            'os_codename'       => $osInfo['codename'] ?? '',
        ];
    }

    private function resolvePackages(
        DOMElement $defNode,
        DOMXPath $xpath,
        array $testMap,
        array $objVarMap,
        array $varPackageMap,
        array $stateVersionMap
    ): array {
        $packages = [];

        /** @var DOMElement $criterion */
        foreach ($xpath->query('.//def:criterion', $defNode) as $criterion) {
            $testRef = $criterion->getAttribute('test_ref');
            if ($testRef === '' || !isset($testMap[$testRef])) {
                continue;
            }

            $info = $testMap[$testRef];
            $version = '';
            if ($info['state_ref'] !== '' && isset($stateVersionMap[$info['state_ref']])) {
                $version = $stateVersionMap[$info['state_ref']];
            }

            if ($info['obj_ref'] === '' || !isset($objVarMap[$info['obj_ref']])) {
                continue;
            }

            $varRef = $objVarMap[$info['obj_ref']];
            if ($varRef === '' || !isset($varPackageMap[$varRef])) {
                continue;
            }

            foreach ($varPackageMap[$varRef] as $pkgName) {
                $key = $pkgName . '=' . $version;
                if (!isset($packages[$key])) {
                    $packages[$key] = ['package' => $pkgName, 'version' => $version];
                }
            }
        }

        return array_values($packages);
    }

    private function extractOsPlatform(DOMXPath $xpath, DOMElement $defNode): array
    {
        $platforms = [];
        /** @var DOMElement $pNode */
        foreach ($xpath->query('.//def:affected/def:platform', $defNode) as $pNode) {
            $platforms[] = trim($pNode->textContent);
        }

        if (empty($platforms)) {
            return [];
        }

        $result = ['name' => '', 'version' => '', 'codename' => ''];

        if (preg_match('/^(.+?)\s+(\d+\.\d+)(?:\s+\w+)*?(?:\(([^)]+)\))?$/i', $platforms[0], $m)) {
            $result['name'] = strtolower(trim($m[1]));
            $result['version'] = $m[2];
            $result['codename'] = $m[3] ?? '';
        }

        return $result;
    }
}

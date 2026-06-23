<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities\Store;

use dhope0000\LXDClient\Model\Database\Database;
use dhope0000\LXDClient\Model\Vulnerabilities\Os\InsertOperatingSystem;
use dhope0000\LXDClient\Model\Vulnerabilities\Vulnerability\FetchVulnerabilityId;
use dhope0000\LXDClient\Model\Vulnerabilities\Vulnerability\InsertVulnerability;
use dhope0000\LXDClient\Model\Vulnerabilities\Vulnerability\UpdateVulnerability;
use dhope0000\LXDClient\Model\Vulnerabilities\Cve\InsertCve;
use dhope0000\LXDClient\Model\Vulnerabilities\Cve\UpdateCve;
use dhope0000\LXDClient\Model\Vulnerabilities\Cve\FetchCveIdsByVuln;
use dhope0000\LXDClient\Model\Vulnerabilities\Cve\DeleteCvesByIds;
use dhope0000\LXDClient\Model\Vulnerabilities\Package\DeletePackagesForVuln;
use dhope0000\LXDClient\Model\Vulnerabilities\Package\InsertVulnerablePackage;

/**
 * Orchestrates storing parsed vulnerability data by delegating
 * individual row operations to Model classes.
 */
class StoreVulnerabilities
{
    private \PDO $db;

    public function __construct(
        Database $database,
        private readonly InsertOperatingSystem $insertOs,
        private readonly FetchVulnerabilityId $fetchVulnId,
        private readonly InsertVulnerability $insertVuln,
        private readonly UpdateVulnerability $updateVuln,
        private readonly InsertCve $insertCve,
        private readonly UpdateCve $updateCve,
        private readonly FetchCveIdsByVuln $fetchCveIds,
        private readonly DeleteCvesByIds $deleteCves,
        private readonly DeletePackagesForVuln $deletePackages,
        private readonly InsertVulnerablePackage $insertPackage,
    ) {
        $this->db = $database->dbObject;
    }

    /**
     * @param array $vulns  Parsed vulnerabilities from ParseOvalXml
     * @param array $os     ['name', 'version', 'codename']
     * @return array        Counts of what was stored
     */
    public function storeAll(array $vulns, array $os): array
    {
        $this->db->beginTransaction();

        try {
            $osId = $this->insertOs->insert($os['name'], $os['version'], $os['codename'] ?? null);

            $counts = ['vulns' => 0, 'cves' => 0, 'packages' => 0];

            foreach ($vulns as $vuln) {
                $vId = $this->storeSingleVuln($vuln, $osId);
                $counts['cves'] += $this->storeCves($vId, $vuln['cves']);
                $counts['packages'] += $this->storePackages($vId, $vuln['affected_packages']);
                $counts['vulns']++;
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }

        return $counts;
    }

    private function storeSingleVuln(array $vuln, int $osId): int
    {
        $existingId = null;
        if ($vuln['usn_id'] !== '') {
            $existingId = $this->fetchVulnId->fetchByUsn($vuln['usn_id']);
        }

        if ($existingId !== null) {
            $this->updateVuln->update(
                $existingId,
                $vuln['title'],
                ucfirst($vuln['severity']),
                $vuln['issued_date'],
                $vuln['description'],
                $osId
            );
            return $existingId;
        }

        return $this->insertVuln->insert(
            $vuln['title'],
            $vuln['usn_id'] !== '' ? $vuln['usn_id'] : null,
            $vuln['usn_url'] !== '' ? $vuln['usn_url'] : null,
            ucfirst($vuln['severity']),
            $vuln['issued_date'],
            $vuln['description'],
            $osId
        );
    }

    private function storeCves(int $vId, array $cves): int
    {
        $existingIds = $this->fetchCveIds->fetch($vId);
        $newIds = [];
        $stored = 0;

        foreach ($cves as $cve) {
            $newIds[] = $cve['id'];
            $cvssScore = ($cve['cvss_score'] !== '' && $cve['cvss_score'] !== null) ? (float) $cve['cvss_score'] : null;
            $publicDate = $this->normalizePublicDate($cve['public_date'] ?? '');

            if (in_array($cve['id'], $existingIds, true)) {
                $this->updateCve->update(
                    $cve['id'],
                    $vId,
                    $cve['priority'] !== '' ? $cve['priority'] : null,
                    $cvssScore,
                    $cve['cvss_severity'] !== '' ? $cve['cvss_severity'] : null,
                    $cve['cvss_vector'] !== '' ? $cve['cvss_vector'] : null,
                    $publicDate
                );
            } else {
                $this->insertCve->insert(
                    $cve['id'],
                    $vId,
                    $cve['priority'] !== '' ? $cve['priority'] : null,
                    $cvssScore,
                    $cve['cvss_severity'] !== '' ? $cve['cvss_severity'] : null,
                    $cve['cvss_vector'] !== '' ? $cve['cvss_vector'] : null,
                    $publicDate
                );
            }

            $stored++;
        }

        $removed = array_values(array_diff($existingIds, $newIds));
        if (!empty($removed)) {
            $this->deleteCves->delete($vId, $removed);
        }

        return $stored;
    }

    private function storePackages(int $vId, array $packages): int
    {
        $this->deletePackages->delete($vId);

        $count = 0;
        foreach ($packages as $pkg) {
            $this->insertPackage->insert(
                $pkg['package'],
                $vId,
                $pkg['version'] !== '' ? $pkg['version'] : null
            );
            $count++;
        }

        return $count;
    }

    private function normalizePublicDate(string $value): ?string
    {
        if ($value === '') {
            return null;
        }

        if (strlen($value) === 8 && is_numeric($value)) {
            $d = \DateTime::createFromFormat('Ymd', $value);
            return $d ? $d->format('Y-m-d') : null;
        }

        return $value;
    }
}

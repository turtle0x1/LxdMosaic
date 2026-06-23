<?php

namespace dhope0000\LXDClient\Tools\Vulnerabilities\Fetch;

use dhope0000\LXDClient\Tools\Vulnerabilities\Store\StoreVulnerabilities;

/**
 * Downloads Ubuntu OVAL XML feeds, parses them, and delegates storage.
 */
class FetchUbuntuVulnerabilities
{
    public function __construct(
        private readonly ParseOvalXml $parseOvalXml,
        private readonly StoreVulnerabilities $storeVulnerabilities,
    ) {
    }

    /**
     * @param array $activeOs [['name','version','codename'], ...]
     * @return array           Summary
     */
    public function update(array $activeOs): array
    {
        $summary = [
            'vulns_stored' => 0,
            'cves_stored' => 0,
            'packages_stored' => 0,
            'errors' => [],
        ];

        foreach ($activeOs as $os) {
            $tmpFile = $this->downloadOvalXml($os['name'], $os['codename']);
            if ($tmpFile === null) {
                $summary['errors'][] = sprintf(
                    'Failed to download OVAL data for %s %s (%s)',
                    $os['name'], $os['version'], $os['codename']
                );
                continue;
            }

            try {
                $vulns = $this->parseOvalXml->parse($tmpFile, 30);
                $stored = $this->storeVulnerabilities->storeAll($vulns, $os);
                $summary['vulns_stored'] += $stored['vulns'];
                $summary['cves_stored'] += $stored['cves'];
                $summary['packages_stored'] += $stored['packages'];
            } catch (\Throwable $e) {
                $summary['errors'][] = sprintf(
                    'Error processing %s %s: %s',
                    $os['name'], $os['codename'], $e->getMessage()
                );
            } finally {
                @unlink($tmpFile);
            }
        }

        return $summary;
    }

    private function downloadOvalXml(string $osName, string $codename): ?string
    {
        $url = "https://security-metadata.canonical.com/oval/com.ubuntu.{$codename}.usn.oval.xml.bz2";

        $ctx = stream_context_create([
            'http' => [
                'timeout' => 120,
                'user_agent' => 'LxdMosaic/1.0',
            ],
        ]);

        $bzipContent = @file_get_contents($url, false, $ctx);
        if ($bzipContent === false) {
            return null;
        }

        $xmlContent = \bzdecompress($bzipContent);
        if ($xmlContent === false) {
            return null;
        }

        $tmpFile = sys_get_temp_dir() . "/{$osName}_{$codename}_usn.oval.xml";
        file_put_contents($tmpFile, $xmlContent);
        return $tmpFile;
    }
}

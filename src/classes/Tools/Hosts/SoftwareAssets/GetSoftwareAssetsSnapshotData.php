<?php

namespace dhope0000\LXDClient\Tools\Hosts\SoftwareAssets;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Tools\Hosts\HasExtension;

class GetSoftwareAssetsSnapshotData
{
    private $commandMap = [
        // Ubuntu / deb
        'apt list --installed' => 'aptParse',
        // Alpine
        'apk info' => 'apkParse',
        // General package managers
        'snap list' => 'snapParse',
        'npm list -g -l  --parseable --silent' => 'npmParse',
        'pip list --format=columns' => 'pipParse',
        'composer g show' => 'composerParse',
    ];

    public function __construct(
        private readonly HostList $hostList,
        private readonly HasExtension $hasExtension
    ) {
    }

    public function get()
    {
        $hosts = $this->hostList->getOnlineHostsWithDetails();

        $output = [];

        foreach ($hosts as $host) {
            $supportsProjects = $this->hasExtension->checkWithHost($host, 'projects');
            $output[$host->getHostId()] = [];
            $allProjects = [[
                'name' => 'default',
                'config' => [],
            ]];

            if ($supportsProjects) {
                $allProjects = $host->projects->all(2);
            }

            foreach ($allProjects as $project) {
                $projectName = $project['name'];

                $output[$host->getHostId()][$projectName] = [];

                if ($supportsProjects) {
                    $host->setProject($projectName);
                }

                $instances = $host->instances->all(1);
                foreach ($instances as $instance) {
                    if ((int) $instance['status_code'] !== 103) {
                        continue;
                    }
                    $instacePackages = [];
                    foreach ($this->commandMap as $command => $fn) {
                        try {
                            $result = $host->instances->execute($instance['name'], $command, true, [], true);
                            if ($result == null) {
                                continue;
                            }
                            $result = $host->instances->logs->read($instance['name'], $result['output'][0]);
                            $parsedPackages = call_user_func([$this, $fn], $result);
                            $instacePackages = array_merge($instacePackages, $parsedPackages);
                            $output[$host->getHostId()][$projectName][$instance['name']] = $instacePackages;
                        } catch (\Throwable) {
                            continue;
                        }
                    }
                }
            }
        }
        return $output;
    }

    public function aptParse($result)
    {
        $lines = explode("\n", trim((string) $result));
        $packages = [];

        foreach ($lines as $line) {
            // Use regex to extract package details
            if (preg_match(
                '/^(?P<name>[\w\.\-\/]+),now (?P<version>[\w\:\-\.]+) (?P<architecture>\w+) \[(?P<status>[^\]]+)\]$/',
                $line,
                $matches
            )) {
                $packages[] = [
                    'manager' => 'apt',
                    'name' => $matches['name'],
                    'version' => $matches['version'],
                    'rev' => null,
                    'tracking' => null,
                    'publisher' => null,
                    'notes' => null,
                    'architecture' => $matches['architecture'],
                    'status' => explode(',', $matches['status']),
                ];
            }
        }

        return $packages;
    }

    public function snapParse($data)
    {
        // Split the input data into lines
        $lines = explode("\n", trim((string) $data));

        // Remove the header line
        array_shift($lines);

        $packages = [];

        foreach ($lines as $line) {
            // Use regex to extract fields
            if (preg_match(
                '/^(?P<name>[\w\-]+)\s+(?P<version>[\w\.\-]+)\s+(?P<rev>\d+)\s+(?P<tracking>[\w\/\-\.]+)\s+(?P<publisher>[\w\.\-✓✪\*\*]+)\s+(?P<notes>.*)$/',
                $line,
                $matches
            )) {
                $packages[] = [
                    'manager' => 'SNAP',
                    'name' => trim($matches['name']),
                    'version' => trim($matches['version']),
                    'rev' => trim($matches['rev']),
                    'tracking' => trim($matches['tracking']),
                    'publisher' => trim($matches['publisher']),
                    'notes' => trim($matches['notes']),
                    'architecture' => null,
                    'status' => 'installed',
                ];
            }
        }

        return $packages;
    }

    public function apkParse($data)
    {
        $lines = explode("\n", (string) $data);
        array_shift($lines); // Remove the first line

        $packages = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Split the package and version using regex
                if (preg_match('/^(.*?)-(.+)$/', $line, $matches)) {
                    $packageName = $matches[1]; // Package name
                    $version = $matches[2];      // Version
                    $packages[] = [
                        'manager' => 'apk',
                        'name' => $packageName,
                        'version' => $version,
                        'rev' => null,
                        'tracking' => null,
                        'publisher' => null,
                        'notes' => null,
                        'architecture' => null,
                        'status' => 'installed',
                    ];
                }
            }
        }
        return $packages;
    }

    public function npmParse($data)
    {
        // Split the output into lines
        $lines = explode("\n", trim((string) $data));

        // Initialize an array to hold package details
        $packages = [];

        // Loop through each line to extract package information
        foreach ($lines as $line) {
            // Skip empty lines
            if (empty($line)) {
                continue;
            } elseif (!str_contains($line, '@')) {
                continue;
            }

            // Split the line into parts
            $parts = explode(':', $line);

            // Get the package path and name/version
            $packagePath = $parts[0];
            $packageInfo = $parts[1];
            if (!str_contains($packageInfo, '@')) {
                if (!str_contains($parts[2], '@')) {
                    continue;
                }
                $packageInfo = $parts[2];
            }

            $packageParts = explode('@', $packageInfo);
            $packageName = $packageParts[0] ?? null;
            $packageVersion = $packageParts[1] ?? null;

            if ($packageName == null) {
                continue;
            }

            // Fill the array with the specified keys
            $packages[] = [
                'manager' => 'npm',
                'name' => $packageName,
                'version' => $packageVersion,
                'rev' => null, // Placeholder for revision, if applicable
                'tracking' => null, // Placeholder for tracking, if applicable
                'publisher' => null, // Placeholder for publisher, if applicable
                'notes' => null, // Placeholder for notes, if applicable
                'architecture' => null, // Placeholder for architecture, if applicable
                'status' => 'installed', // Default status
            ];
        }
        return $packages;
    }

    public function pipParse($data)
    {
        // Split the output into lines
        $lines = explode("\n", trim((string) $data));

        // Initialize an array to hold package details
        $packages = [];

        // Loop through each line to extract package information
        foreach ($lines as $line) {
            // Skip the header and empty lines
            if (str_contains($line, 'Package') || empty(trim($line))) {
                continue;
            }

            // Split the line into parts based on whitespace
            $parts = preg_split('/\s+/', trim($line));

            // Extract package name and version
            $packageName = $parts[0];
            $packageVersion = $parts[1];

            // Fill the array with the specified keys
            $packages[] = [
                'manager' => 'pip',
                'name' => $packageName,
                'version' => $packageVersion,
                'rev' => null, // Placeholder for revision, if applicable
                'tracking' => null, // Placeholder for tracking, if applicable
                'publisher' => null, // Placeholder for publisher, if applicable
                'notes' => null, // Placeholder for notes, if applicable
                'architecture' => null, // Placeholder for architecture, if applicable
                'status' => 'installed', // Default status
            ];
        }
        return $packages;
    }

    public function composerParse($data)
    {
        // Split the output into data
        $lines = explode("\n", trim((string) $data));

        // Initialize an array to hold package details
        $packages = [];

        // Loop through each line to extract package information
        foreach ($lines as $line) {
            // Skip the header and empty lines
            if (empty(trim($line)) || str_contains($line, 'Changed current directory')) {
                continue;
            }

            // Split the line into parts based on whitespace
            $parts = preg_split('/\s+/', trim($line), 3); // Limit to 3 parts to separate name/version from description

            // Extract package name, version, and description
            if (count($parts) >= 2) {
                $packageName = $parts[0];
                $packageVersion = $parts[1];
                $packageDescription = $parts[2] ?? '';

                // Fill the array with the specified keys
                $packages[] = [
                    'manager' => 'composer',
                    'name' => $packageName,
                    'version' => $packageVersion,
                    'rev' => null, // Placeholder for revision, if applicable
                    'tracking' => null, // Placeholder for tracking, if applicable
                    'publisher' => null, // Placeholder for publisher, if applicable
                    'notes' => $packageDescription, // Use description as notes
                    'architecture' => null, // Placeholder for architecture, if applicable
                    'status' => 'installed', // Default status
                ];
            }
        }
        return $packages;
    }
}

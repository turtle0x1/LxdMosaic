<?php

namespace dhope0000\LXDClient\Tools\Instances;

use dhope0000\LXDClient\Model\Instances\InstanceTypes\FetchInstanceType;
use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Hosts\Images\ImportImageIfNotHave;
use dhope0000\LXDClient\Tools\Hosts\Instances\HostsHaveInstance;

class CreateInstance
{
    public function __construct(
        private readonly HostsHaveInstance $hostsHaveInstance,
        private readonly ImportImageIfNotHave $importImageIfNotHave,
        private readonly FetchInstanceType $fetchInstanceType
    ) {
    }

    /**
     * TODO Find out the $server param and send it to space
     */
    public function create(
        string $type,
        string $name,
        array $profiles,
        HostsCollection $hosts,
        array $imageDetails,
        $server = '',
        string $instanceType = '',
        ?array $gpus = null,
        array $config = [],
        bool $start = false
    ) {
        $this->hostsHaveInstance->ifHostInListHasContainerNameThrow($hosts, $name);

        $options = $this->createOptionsArray(
            $type,
            $profiles,
            $imageDetails,
            $server,
            $instanceType,
            $gpus,
            $config
        );

        $results = [];

        foreach ($hosts as $host) {
            if (isset($imageDetails['empty']) && $imageDetails['empty']) {
                $options['source'] = [
                    'type' => 'none',
                ];
            } else {
                $newFingerPrint = $this->importImageIfNotHave->importIfNot($host, $imageDetails);
                if ($newFingerPrint !== $options['fingerprint']) {
                    $options['fingerprint'] = $newFingerPrint;
                }
            }

            $alias = '';
            // Thats expensive
            $clusterDetails = $host->cluster->info();
            if ($clusterDetails['enabled']) {
                $alias = $clusterDetails['server_name'];
            }

            $response = $host->instances->create($name, $options, true, [], $alias);

            if ($response['status_code'] == 400) {
                throw new \Exception("Host: {$host->getUrl()} " . $response['err'], 1);
            }

            if ($start) {
                $host->instances->start($name, 30, true, false, true);
            }

            $results[] = $response;
        }

        return $results;
    }

    private function createOptionsArray(
        $type,
        $profiles,
        $imageDetails,
        $server = '',
        $instanceType = '',
        ?array $gpus = null,
        array $config = []
    ) {
        $x = [
            'type' => $type,
            'fingerprint' => $imageDetails['fingerprint'],
            'profiles' => $profiles,
            'server' => $server,
        ];
        if (is_array($gpus) && !empty($gpus)) {
            $x['devices'] = [];
            foreach ($gpus as $index => $id) {
                $x['devices']["gpu_{$index}"] = [
                    'type' => 'gpu',
                    'pci' => $id,
                ];
            }
        }

        if (!empty($config)) {
            $x['config'] = $config;
        }

        if ($instanceType !== '') {
            $instanceType = $this->fetchInstanceType->fetchByName($instanceType);

            if (!isset($x['config'])) {
                $x['config'] = [];
            }

            // From here https://github.com/lxc/lxd/blob/e1761309baa166d32e449fb5bb369481c9a456f1/lxd/instance_instance_types.go#L262
            $cpuCores = (int) $instanceType['cpu'];
            if ((float) $cpuCores < $instanceType['cpu']) {
                $cpuCores++;
            }

            $cpuTime = (int) ($instanceType['cpu'] / (float) $cpuCores * 100.0);

            $x['config']['limits.cpu'] = (string) $cpuCores;
            if ($cpuTime < 100) {
                $x['config']['limits.cpu.allowance'] = $cpuTime . '%';
            }

            $x['config']['limits.memory'] = (int) ($instanceType['mem'] * 1024) . 'MB';
        }

        return $x;
    }
}

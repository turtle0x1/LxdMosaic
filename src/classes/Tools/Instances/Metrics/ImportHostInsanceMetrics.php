<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Model\Metrics\InsertMetric;
use dhope0000\LXDClient\Model\Metrics\Types\FetchType;
use dhope0000\LXDClient\Objects\Host;

class ImportHostInsanceMetrics
{
    // So we dont have to call the db for template key ids every time
    private $keyCache = [];

    public function __construct(
        private readonly FetchType $fetchType,
        private readonly InsertMetric $insertMetric
    ) {
    }

    public function import(Host $host, array $instancesToScan)
    {
        //TODO Should probably check that the host supports this extension
        //     but how old is that host (Wish the LXD docs were clearer)?
        $instances = $host->instances->all(LxdRecursionLevels::INSTANCE_FULL_RECURSION);

        foreach ($instances as $index => $instance) {
            $instances[$instance['name']] = $instance;
            unset($instances[$index]);
        }

        foreach ($instancesToScan as $instance) {
            $state = $instances[$instance]['state'];

            if ($state['status_code'] !== 103) {
                continue;
            }
            // Can always gatther these two thanks to the LXD API
            $this->addInstanceNetworkUsage($host, $instance, $state);
            $this->addInstanceMemoryUsage($host, $instance, $state);

            // CPU Details
            if ($host->hostSupportLoadAvgs()) {
                $this->addInstanceLoadAverage($host, $instance);
            }

            // Nvidia GPU support
            if (isset($instances[$instance]['expanded_config']['nvidia.runtime'])) {
                $this->addInstanceNvidiaGpuUsage($host, $instance, $state);
            }

            // Storage Details
            if (!empty($state['disk'])) {
                $this->addInstanceStorageUsage($host, $instance, $state);
            }
        }
    }

    private function addInstanceNvidiaGpuUsage($host, $instance, $state)
    {
        $command = 'nvidia-smi --query-gpu=name,gpu_uuid,temperature.gpu,utilization.gpu,utilization.memory,memory.total,memory.free,memory.used --format=csv';
        $lxdResponse = $host->instances->execute($instance, $command, $record = true, [], true);
        $output = array_filter(
            explode("\n", (string) $host->instances->logs->read($instance, $lxdResponse['output'][0]))
        );

        $host->instances->logs->remove($instance, $lxdResponse['output'][0]);
        $host->instances->logs->remove($instance, $lxdResponse['output'][1]);

        if (empty($output)) {
            // Throwing an exception would interupt exectution but im adding this
            // because this what happens if the nvidia.runtime is set but
            // the nvidia-smi fails
            return false;
        }

        unset($output[0]);
        $csv = array_map('str_getcsv', $output);
        $gpuDetails = [];
        foreach ($csv as $gpu) {
            $gpu = array_map('trim', $gpu);
            $gpuDetails["{$gpu[0]} temperature (Id: {$gpu[1]}"] = $gpu[2];
            $gpuDetails["{$gpu[0]} utilization % (Id: {$gpu[1]}"] = explode(' ', $gpu[3])[0];
            $gpuDetails["{$gpu[0]} memory utilization % (Id: {$gpu[1]}"] = explode(' ', $gpu[4])[0];
            $gpuDetails["{$gpu[0]} memory total MiB (Id: {$gpu[1]}"] = explode(' ', $gpu[5])[0];
            $gpuDetails["{$gpu[0]} memory free MiB (Id: {$gpu[1]}"] = explode(' ', $gpu[6])[0];
            $gpuDetails["{$gpu[0]} memory used MiB (Id: {$gpu[1]}"] = explode(' ', $gpu[7])[0];
        }

        $metricKey = 'nvidiaGpuDetails';
        $this->matchTypeAndStore(
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            $host,
            $instance,
            [
                $metricKey => $gpuDetails,
            ]
        );
    }

    private function addInstanceLoadAverage($host, $instance)
    {
        $output = $host->instances->execute($instance, 'cat /proc/loadavg', $record = true, [], true);

        $averages = trim((string) $host->instances->logs->read($instance, $output['output'][0]));

        $host->instances->logs->remove($instance, $output['output'][0]);
        $host->instances->logs->remove($instance, $output['output'][1]);

        $x = explode(' ', $averages);
        $dateTime = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $matched = $this->matchTypeAndStore($dateTime, $host, $instance, [
            'loadAvg' => [
                '1 minute' => $x[0],
                '5 minutes' => $x[1],
                '15 minutes' => $x[2],
            ],
        ]);
        return true;
    }

    private function addInstanceStorageUsage($host, $instance, $instanceState)
    {
        $metricKey = 'storageUsage';

        $storageDetails = [];

        foreach ($instanceState['disk'] as $disk => $details) {
            $storageDetails["{$disk} usage"] = $details['usage'];
        }

        ksort($storageDetails);

        $this->matchTypeAndStore(
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            $host,
            $instance,
            [
                $metricKey => $storageDetails,
            ]
        );
    }

    private function addInstanceNetworkUsage($host, $instance, $instanceState)
    {
        $metricKey = 'networkUsage';

        $networkDetails = [];
        $networkData = [];
        foreach ($instanceState['network'] as $name => $network) {
            foreach ($network['counters'] as $key => $value) {
                $networkData["{$name} {$key}"] = $value;
            }
        }

        ksort($networkData);

        $this->matchTypeAndStore(
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            $host,
            $instance,
            [
                $metricKey => $networkData,
            ]
        );
    }

    private function addInstanceMemoryUsage($host, $instance, $instanceState)
    {
        $key = 'memoryUsage';

        $this->matchTypeAndStore(
            (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            $host,
            $instance,
            [
                $key => $instanceState['memory'],
            ]
        );
    }

    private function matchTypeAndStore($date, $host, $container, $content)
    {
        $output = [];
        foreach ($content as $key => $value) {
            if (!isset($this->keyCache[$key])) {
                $this->keyCache[$key] = $this->fetchType->fetchIdByTemplateKey($key);
                //TODO If we fail on finding a log type, store the log,
                //     delete it if it came from a file
            }

            $this->insertMetric->insert(
                $date,
                $host->getHostId(),
                $container,
                $this->keyCache[$key],
                json_encode($value)
            );
        }
        return $output;
    }
}

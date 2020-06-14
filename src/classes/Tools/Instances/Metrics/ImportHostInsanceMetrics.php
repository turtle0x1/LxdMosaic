<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Metrics\Types\FetchType;
use dhope0000\LXDClient\Model\Metrics\InsertMetric;

class ImportHostInsanceMetrics
{
    // So we dont have to call the db for template key ids every time
    private $keyCache = [];
    private $fetchType;
    private $insertMetric;

    public function __construct(
        FetchType $fetchType,
        InsertMetric $insertMetric
    ) {
        $this->fetchType = $fetchType;
        $this->insertMetric = $insertMetric;
    }

    public function import($host, $instancesToScan)
    {
        $instances = $host->instances->all(2);
        foreach ($instances as $index => $instance) {
            $instances[$instance["name"]] = $instance;
            unset($instances[$index]);
        }
        foreach ($instancesToScan as $instance) {
            $state = $instances[$instance]["state"];
            $this->importCpuLoadAverage($host, $instance);
            $this->addInstanceMemoryUsage($host, $instance, $state);
            $this->addInstanceNetworkUsage($host, $instance, $state);
            $this->addInstanceStorageUsage($host, $instance, $state);
        }
    }

    private function importCpuLoadAverage($host, $instance)
    {
        // Import CPU Load averages
        try {
            $path = "/etc/lxdMosaic/offlineLogs/";
            $files = $host->instances->files->read($instance, $path);

            if (empty($files)) {
                return true;
            }

            foreach ($files as $file) {
                $dateTime = (new \DateTime(str_replace(".json", "", $file)))->format("Y-m-d H:i:s");
                $content = $host->instances->files->read($instance, "/etc/lxdMosaic/offlineLogs/" . $file);
                $content = json_decode($content, true);
                $matched = $this->matchTypeAndStore($dateTime, $host, $instance, $content);
                $host->instances->files->remove($instance, "/etc/lxdMosaic/offlineLogs/" . $file);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            //TODO HMm what todo here
            return false;
        }
    }

    private function addInstanceStorageUsage($host, $instance, $instanceState)
    {
        $metricKey = "storageUsage";

        $storageDetails = [];

        foreach ($instanceState["disk"] as $disk => $details) {
            $storageDetails["$disk usage"] = $details["usage"];
        }

        $this->matchTypeAndStore(
            (new \DateTimeImmutable())->format("Y-m-d H:i:s"),
            $host,
            $instance,
            [$metricKey=>$storageDetails]
        );
    }
    private function addInstanceNetworkUsage($host, $instance, $instanceState)
    {
        $metricKey = "networkUsage";

        $networkDetails = [];

        foreach ($instanceState["network"] as $name => $network) {
            $networkData = [];
            foreach ($network["counters"] as $key => $value) {
                $networkData["$name $key"] = $value;
            }
            $this->matchTypeAndStore(
                (new \DateTimeImmutable())->format("Y-m-d H:i:s"),
                $host,
                $instance,
                [$metricKey=>$networkData]
            );
        }
    }

    private function addInstanceMemoryUsage($host, $instance, $instanceState)
    {
        $key = "memoryUsage";

        $this->matchTypeAndStore(
            (new \DateTimeImmutable())->format("Y-m-d H:i:s"),
            $host,
            $instance,
            [$key=>$instanceState["memory"]]
        );
    }

    private function matchTypeAndStore($date, $host, $container, $content)
    {
        $output = [];
        foreach ($content as $key=>$value) {
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

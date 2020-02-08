<?php

namespace dhope0000\LXDClient\Tools\Instances\Metrics;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Model\Metrics\Types\FetchType;
use dhope0000\LXDClient\Model\Metrics\InsertMetric;

/**

 */
class ImportHostInsanceMetrics
{
    // So we dont have to call the db for template key ids every time
    private $keyCache = [];

    public function __construct(
        LxdClient $lxdClient, FetchType $fetchType, InsertMetric $insertMetric)
    {
        $this->lxdClient = $lxdClient;
        $this->fetchType = $fetchType;
        $this->insertMetric = $insertMetric;
    }

    public function import($hostId, $instancesToScan)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        $path = "/etc/lxdMosaic/offlineLogs/";
        foreach($instancesToScan as $instance){
            // This is where async programming comes in handy, i see it now
            try {
                $files = $client->instances->files->read($instance, $path);

                if(empty($files)){
                    continue;
                }

                foreach($files as $file){
                    $dateTime = (new \DateTime(str_replace(".json", "", $file)))->format("Y-m-d H:i:s");
                    $content = $client->instances->files->read($instance, "/etc/lxdMosaic/offlineLogs/" . $file);
                    $content = json_decode($content, true);
                    $matched = $this->matchTypeAndStore($dateTime, $hostId, $instance, $content);
                    $client->instances->files->remove($instance, "/etc/lxdMosaic/offlineLogs/" . $file);
                }


            } catch (\GuzzleHttp\Exception\ClientException $e) {
                continue;
            }

        }
    }

    private function matchTypeAndStore($date, $hostId, $container, $content)
    {
        $output = [];
        foreach($content as $key=>$value){
            if(!isset($this->keyCache[$key])){
                $this->keyCache[$key] = $this->fetchType->fetchIdByTemplateKey($key);
                //TODO If we fail on finding a log type, store the log,
                //     delete it if it came from a file
            }

            $this->insertMetric->insert(
                $date,
                $hostId,
                $container,
                $this->keyCache[$key],
                json_encode($value)
            );
        }
        return $output;
    }
}

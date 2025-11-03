<?php
namespace dhope0000\LXDClient\Tools\Storage;

use dhope0000\LXDClient\Objects\HostsCollection;

class GetStorageOnHosts
{
    public function getCommon(HostsCollection $hosts)
    {    
        $allHosts = $hosts->getAllHosts();
        $totalHosts = count($allHosts);
        if(count($allHosts) == 1){
            return $allHosts[0]->storage->all();
        }
        $storagePools = [];
        $output = [];
        foreach($allHosts as $host){
            $pools = $host->storage->all();;
            foreach($pools as $pool){
                if(!isset($storagePools[$pool])){
                    $storagePools[$pool] = 0;
                }
                $storagePools[$pool]++;
                if($storagePools[$pool] == $totalHosts){
                    $output[] = $pool;
                }
            }
        }
        return $output;
    }
}

<?php

namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Objects\HostsCollection;

class ImportRemoteImagesByFingerprint
{
    public function import(HostsCollection $hosts, array $images, string $urlKey) :array
    {
        $urlMap = [
            "linuxcontainers"=>'https://images.linuxcontainers.org',
            "ubuntu-release"=>'https://cloud-images.ubuntu.com/releases',
            "ubuntu-daily"=>'https://cloud-images.ubuntu.com/daily'
        ];
        $output = [];
        foreach ($hosts as $host) {
            foreach ($images as $image) {
                $output[] = $host->images->createFromRemote(
                    $urlMap[$urlKey],
                    [
                        "protocol"=>"simplestreams",
                        "fingerprint"=>$image["fingerprint"],
                    ]
                );
            }
        }
        return $output;
    }
}

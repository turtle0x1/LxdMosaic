<?php

namespace dhope0000\LXDClient\Tools\Hosts\Images;

use dhope0000\LXDClient\Tools\Hosts\Images\HostHasImage;
use Opensaucesystems\Lxd\Client;

class ImportImageIfNotHave
{
    public function __construct(HostHasImage $hostHasImage)
    {
        $this->hostHasImage = $hostHasImage;
    }

    public function importIfNot(Client $client, array $imageDetails)
    {
        if ($this->hostHasImage->has($client, $imageDetails["fingerprint"])) {
            return true;
        }

        //By default we can just the alias (if the image is from linuxcontainers.org)
        $detailsToUse = ["alias"=>$imageDetails["alias"]];

        // Ubuntu images on the other hand (if imported ubuntu not from
        // linuxcontainers but the default method ) will be difficult
        // and need this work around if the host doesn't have the ubuntu
        // image
        if ($imageDetails["alias"] == "default") {
            $detailsToUse = [
                "fingerprint"=>$imageDetails["fingerprint"],
                "protocol"=>$imageDetails["protocol"]
            ];
        }

        //NOTE we are using wait here so for large images this is blocking
        //     (lengthing the request)
        $this->lxdResponse = $client->images->createFromRemote(
            $imageDetails["server"],
            $detailsToUse,
            false,
            true
        );

        return $imageDetails;
    }
}

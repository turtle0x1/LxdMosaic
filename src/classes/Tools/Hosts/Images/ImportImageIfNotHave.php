<?php

namespace dhope0000\LXDClient\Tools\Hosts\Images;

use dhope0000\LXDClient\Tools\Hosts\Images\HostHasImage;
use dhope0000\LXDClient\Objects\Host;

class ImportImageIfNotHave
{
    private HostHasImage $hostHasImage;

    public function __construct(HostHasImage $hostHasImage)
    {
        $this->hostHasImage = $hostHasImage;
    }

    /**
     * Will return the new fingerprint of the image
     */
    public function importIfNot(Host $host, array $imageDetails) :string
    {
        if ($this->hostHasImage->has($host, $imageDetails["fingerprint"])) {
            return $imageDetails["fingerprint"];
        }

        if (isset($imageDetails["provideMyHostsCert"])) {
            $provideCerts = $imageDetails["provideMyHostsCert"];
            if (is_string($provideCerts)) {
                $provideCerts = $provideCerts  === "true" ? true : false;
            }

            if ($provideCerts) {
                $imageDetails["certificate"] = $x = \dhope0000\LXDClient\Tools\Hosts\Certificates\GetHostCertificate::get(str_replace("https://", "", $imageDetails["server"]));
            }
        }



        //NOTE we are using wait here so for large images this is blocking
        //     (lengthing the request)
        $response = $host->images->createFromRemote(
            $imageDetails["server"],
            $imageDetails,
            false,
            true
        );

        if (!empty($response["err"])) {
            throw new \Exception($response["err"], 1);
        }

        return $response["metadata"]["fingerprint"];
    }
}

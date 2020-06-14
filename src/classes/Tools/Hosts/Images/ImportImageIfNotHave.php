<?php

namespace dhope0000\LXDClient\Tools\Hosts\Images;

use dhope0000\LXDClient\Tools\Hosts\Images\HostHasImage;
use dhope0000\LXDClient\Objects\Host;

class ImportImageIfNotHave
{
    public function __construct(HostHasImage $hostHasImage)
    {
        $this->hostHasImage = $hostHasImage;
    }

    public function importIfNot(Host $host, array $imageDetails)
    {
        if ($this->hostHasImage->has($host, $imageDetails["fingerprint"])) {
            return true;
        }

        if (isset($imageDetails["provideMyHostsCert"]) &&  $imageDetails["provideMyHostsCert"] == true) {
            $imageDetails["certificate"] = $x = \dhope0000\LXDClient\Tools\Hosts\Certificates\GetHostCertificate::get(str_replace("https://", "", $imageDetails["server"]));
        }

        //NOTE we are using wait here so for large images this is blocking
        //     (lengthing the request)
        $this->lxdResponse = $host->images->createFromRemote(
            $imageDetails["server"],
            $imageDetails,
            false,
            true
        );

        if (!empty($this->lxdResponse["err"])) {
            throw new \Exception($this->lxdResponse["err"], 1);
        }

        return $imageDetails;
    }
}

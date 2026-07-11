<?php

namespace dhope0000\LXDClient\Tools\Hosts\Images;

use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Objects\Host;

class ImportImageIfNotHave
{
    public function __construct(
        private readonly HostHasImage $hostHasImage,
        private readonly GetDetails $getDetails
    ) {
    }

    /**
     * Will return the new fingerprint of the image
     */
    public function importIfNot(Host $host, array $imageDetails): string
    {
        if ($this->hostHasImage->has($host, $imageDetails['fingerprint'])) {
            return $imageDetails['fingerprint'];
        }

        $this->checkIfSourceServerUsesSocket($imageDetails);

        if (isset($imageDetails['provideMyHostsCert'])) {
            $provideCerts = $imageDetails['provideMyHostsCert'];
            if (is_string($provideCerts)) {
                $provideCerts = $provideCerts === 'true' ? true : false;
            }

            if ($provideCerts) {
                $imageDetails['certificate'] = $x = \dhope0000\LXDClient\Tools\Hosts\Certificates\GetHostCertificate::get(
                    str_replace('https://', '', $imageDetails['server'])
                );
            }
        }

        //NOTE we are using wait here so for large images this is blocking
        //     (lengthing the request)
        $response = $host->images->createFromRemote($imageDetails['server'], $imageDetails, false, true);

        if (!empty($response['err'])) {
            throw new \Exception($response['err'], 1);
        }

        return $response['metadata']['fingerprint'];
    }

    private function checkIfSourceServerUsesSocket(array $imageDetails): void
    {
        if (!isset($imageDetails['server'])) {
            return;
        }

        $sourceHost = $this->getDetails->fetchHostByUrl($imageDetails['server']);

        if ($sourceHost && $sourceHost->usesSocket()) {
            throw new \Exception("The source server '{$imageDetails['server']}' is connected via a Unix socket. Pulling images from a Unix socket-based host is not supported as proxying over Unix sockets is not available.", 1);
        }
    }
}

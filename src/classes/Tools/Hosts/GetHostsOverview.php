<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetHostsOverview
{
    private HostList $hostList;
    private GetDetails $getDetails;

    public function __construct(
        HostList $hostList,
        GetDetails $getDetails
    ) {
        $this->hostList = $hostList;
        $this->getDetails = $getDetails;
    }

    /**
     * Assumes user is admin
     */
    public function get()
    {
        $hosts = $this->hostList->fetchAllHosts();
        foreach ($hosts as $host) {
            $socketPath = $this->getDetails->getSocketPath($host->getHostId());
            $certExpires = new \DateTime("9999-12-31");

            if ($socketPath === null) {
                $cert = file_get_contents($_ENV["LXD_CERTS_DIR"] . $host->getCertPath());

                if ($cert == false) {
                    throw new \Exception("Couldn't find cert at path {$host->getCertPath()}", 1);
                }

                $certinfo = openssl_x509_parse($cert);

                if ($certinfo == false) {
                    throw new \Exception("Couldn't parse cert at path {$host->getCertPath()}", 1);
                }

                $certExpires = \DateTime::createFromFormat('U', $certinfo["validTo_time_t"]);

                if ($certExpires == false) {
                    throw new \Exception("Couldn't extract valid date from {$host->getCertPath()}", 1);
                }
            }
            $host->setCustomProp("certExpires", $certExpires->format("Y-m-d H:i:s"));
        }
        return $hosts;
    }
}

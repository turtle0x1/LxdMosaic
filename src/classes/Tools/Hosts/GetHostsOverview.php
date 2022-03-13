<?php

namespace dhope0000\LXDClient\Tools\Hosts;

use dhope0000\LXDClient\Model\Hosts\HostList;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetHostsOverview
{
    private $hostList;

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

                $certinfo = openssl_x509_parse($cert);
                $certExpires = \DateTime::createFromFormat('U', $certinfo["validTo_time_t"]);
            }
            $host->setCustomProp("certExpires", $certExpires->format("Y-m-d H:i:s"));
        }
        return $hosts;
    }
}

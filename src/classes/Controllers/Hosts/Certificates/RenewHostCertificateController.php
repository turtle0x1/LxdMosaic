<?php

namespace dhope0000\LXDClient\Controllers\Hosts\Certificates;

use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Certificates\RenewCert;
use Symfony\Component\Routing\Annotation\Route;

class RenewHostCertificateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly RenewCert $renewCert,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    /**
     * @Route("/api/Hosts/Certificates/RenewHostCertificateController/renew", name="Renew LXDMosaic certificate with LXD", methods={"POST"})
     */
    public function renew(int $userId, Host $host)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception('No access', 1);
        }
        $this->renewCert->renew($host);
        return [
            'state' => 'success',
            'messages' => 'Renewed Certificate',
        ];
    }
}

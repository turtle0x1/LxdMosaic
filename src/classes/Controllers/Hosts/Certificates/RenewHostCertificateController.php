<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Certificates;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Certificates\RenewCert;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;
use Symfony\Component\Routing\Annotation\Route;

class RenewHostCertificateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private $renewCert;
    private $fetchUserDetails;

    public function __construct(RenewCert $renewCert, FetchUserDetails $fetchUserDetails)
    {
        $this->renewCert = $renewCert;
        $this->fetchUserDetails = $fetchUserDetails;
    }
    /**
     * @Route("/api/Hosts/Certificates/RenewHostCertificateController/renew", name="Renew LXDMosaic certificate with LXD", methods={"POST"})
     */
    public function renew(int $userId, Host $host)
    {
        $isAdmin = $this->fetchUserDetails->isAdmin($userId);
        if (!$isAdmin) {
            throw new \Exception("No access", 1);
        }
        $this->renewCert->renew($host);
        return ["state"=>"success", "messages"=>"Renewed Certificate"];
    }
}

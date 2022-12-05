<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Certificates;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Hosts\Certificates\RenewCert;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class RenewHostCertificateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private RenewCert $renewCert;
    private ValidatePermissions $validatePermissions;

    public function __construct(RenewCert $renewCert, ValidatePermissions $validatePermissions)
    {
        $this->renewCert = $renewCert;
        $this->validatePermissions = $validatePermissions;
    }
    /**
     * @Route("", name="Renew LXDMosaic certificate with LXD")
     */
    public function renew(int $userId, Host $host)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->renewCert->renew($host);
        return ["state"=>"success", "messages"=>"Renewed Certificate"];
    }
}

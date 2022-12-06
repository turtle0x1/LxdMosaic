<?php

namespace dhope0000\LXDClient\Controllers\InstanceSettings\Ldap;

use dhope0000\LXDClient\Tools\InstanceSettings\Ldap\SaveLdapSettings;
use Symfony\Component\Routing\Annotation\Route;

class SaveLdapSettingsController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    private SaveLdapSettings $saveLdapSettings;

    public function __construct(SaveLdapSettings $saveLdapSettings)
    {
        $this->saveLdapSettings = $saveLdapSettings;
    }
    /**
     * @Route("", name="Save LXDMosaic Ldap Settings")
     */
    public function save(int $userId, array $settings)
    {
        $this->saveLdapSettings->save($userId, $settings);
        return ["state"=>"success", "message"=>"Saved Ldap Settings"];
    }
}

<?php

namespace dhope0000\LXDClient\Tools\InstanceSettings;

use dhope0000\LXDClient\Model\InstanceSettings\GetSetting;
use dhope0000\LXDClient\Constants\InstanceSettingsKeys;

class CreatePhoneHomeVendorString
{
    public function __construct(GetSetting $getSetting)
    {
        $this->getSetting = $getSetting;
    }

    public function create(int $deploymentId) :string
    {
        $phonehomeLocation = $this->getSetting->getSettingLatestValue(InstanceSettingsKeys::INSTANCE_IP);

        return "#cloud-config

        phone_home:
            url: http://$phonehomeLocation:8001/deploymentProgress/$deploymentId
            post: [ pub_key_dsa, pub_key_rsa, pub_key_ecdsa, instance_id, hostname, fqdn ]
            tries: 10
        ";
    }
}

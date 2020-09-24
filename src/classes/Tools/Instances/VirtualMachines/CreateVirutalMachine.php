<?php

namespace dhope0000\LXDClient\Tools\Instances\VirtualMachines;

use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreateVirutalMachine
{
    public function __construct(
        CreateInstance $createInstance
    ) {
        $this->createInstance = $createInstance;
    }

    public function create(string $name, string $username, HostsCollection $hosts, array $imageDetails)
    {
        $config = [];
        $config["user.vendor-data"] = $this->getVendorData($username);

        $profileName = "VM-$username-" . (new \DateTime())->format("Y-m-d_H_i_s");

        $description = "Created by LXDMosaic";

        foreach ($hosts as $host) {
            $host->profiles->create(
                $profileName,
                $description,
                $config,
                [
                    "config"=>[
                        "source"=>"cloud-init:config",
                        "type"=>"disk"
                    ]
                ]
            );
        }

        $this->createInstance->create(
            LxdInstanceTypes::VM,
            $name,
            [$profileName, "default"],
            $hosts,
            $imageDetails
        );

        return true;
    }

    private function getVendorData(string $username)
    {
        return '#cloud-config
            users:
             - name: ' . $username . '
               passwd: "$6$iBF0eT1/6UPE2u$V66Rk2BMkR09pHTzW2F.4GHYp3Mb8eu81Sy9srZf5sVzHRNpHP99JhdXEVeN0nvjxXVmoA6lcVEhOOqWEd3Wm0"
               lock_passwd: false
               groups: lxd
               shell: /bin/bash
               sudo: ALL=(ALL) NOPASSWD:ALL

            runcmd:
             - mount -t 9p config /mnt/
             - cd /mnt && ./install.sh
             - reboot';
    }
}

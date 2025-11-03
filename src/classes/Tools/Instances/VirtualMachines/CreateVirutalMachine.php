<?php

namespace dhope0000\LXDClient\Tools\Instances\VirtualMachines;

use dhope0000\LXDClient\Constants\LxdInstanceTypes;
use dhope0000\LXDClient\Constants\LxdRecursionLevels;
use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Instances\CreateInstance;
use dhope0000\LXDClient\Objects\HostsCollection;

class CreateVirutalMachine
{
    private $createInstance;
    private const MOSAIC_TRACK_KEY = "user.mosaic-source-fingerprint";

    public function __construct(
        CreateInstance $createInstance
    ) {
        $this->createInstance = $createInstance;
    }

    public function create(
        string $name,
        string $username,
        HostsCollection $hosts,
        bool $start,
        array $imageDetails = [],
        array $config = []
    ) {
        $profileConfig = [];
        
        $profileName = "vm-template";
        $description = "Created by LXDMosaic - " . (new \DateTime())->format("Y-m-d_H_i_s");

        $usingCustomImage = $imageDetails["imageType"] === "iso";
        $filehash = null;
        if ($usingCustomImage) {
            $filehash = hash_file('sha256', $_FILES["file"]["tmp_name"]);
            $profileConfig[self::MOSAIC_TRACK_KEY] = $filehash;

            $profileName .= "-" . $imageDetails["isoName"];
            $profileDevices = [
                "boot-vol" => [
                    "type" => "disk",
                    "pool" => $imageDetails["pool"],
                    "source" => $imageDetails["isoName"],
                    "boot.priority" => "10"
                ]
            ];
        } else {
            $profileConfig = $this->getVendorData($username);
            $profileName .= "-" . ($imageDetails["alias"] ?? $imageDetails["fingerprint"]);
            $profileDevices = [
                "config" => [
                    "source" => "cloud-init:config",
                    "type" => "disk"
                ]
            ];
        }
        unset($imageDetails["type"]);

        foreach ($hosts as $host) {
            $existingProfile = $this->hostHasProfile($host, $filehash);
            if ($existingProfile === false) {
                if ($usingCustomImage) {
                    $x = $host->storage->volumes->createCustomVolumeFromFile($imageDetails["pool"], $imageDetails["isoName"], file_get_contents($_FILES["file"]["tmp_name"]), "iso", true);
                    if (isset($x["type"]) && $x["type"] === "error") {
                        throw new \Exception($x["error"]);
                    }
                }

                $host->profiles->create(
                    $profileName,
                    $description,
                    $profileConfig,
                    $profileDevices
                );
                $existingProfile = $profileName;
            }
            $this->createInstance->create(
                LxdInstanceTypes::VM,
                $name,
                [$existingProfile, "default"],
                new HostsCollection([$host]),
                $usingCustomImage ? ["empty" => true] : $imageDetails,
                "",
                "",
                null,
                $config,
                $start
            );
        }

        return true;
    }

    private function hostHasProfile(Host $host, string $fingerprint)
    {
        $profiles = $host->profiles->all(LxdRecursionLevels::INSTANCE_HALF_RECURSION);
        foreach ($profiles as $profile) {
            if (isset($profile["config"]) && isset($profile["config"][self::MOSAIC_TRACK_KEY]) && $profile["config"][self::MOSAIC_TRACK_KEY] === $fingerprint) {
                return $profile["name"];
            }
        }
        return false;
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
         ';
    }
}

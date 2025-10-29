<?php

namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Constants\ImageServerType;
use dhope0000\LXDClient\Model\InstanceSettings\ImageServers\FetchImageServers;

class SearchRemoteImages
{
    private $fetchImageServers;

    public function __construct(FetchImageServers $fetchImageServers)
    {
        $this->fetchImageServers = $fetchImageServers;
    }

    public function get($urlKey = "linuxcontainers", $searchType = "container", $searchArch = "amd64")
    {
        $imageServer = $this->fetchImageServers->fetchAliasDetails($urlKey);
        if ($imageServer == false) {
            throw new \Exception("Server Not Found");
        }

        $url = $imageServer["url"];

        $isUbuntuRelaseServer = (int) $imageServer["protocol"] === ImageServerType::UBUNTU_RELEASE;

        $server = $this->getSimpleStreamsJson($url);
        $products = $server["products"];

        $containerFsList = ["combined_sha256", "combined_rootxz_sha256", "combined_squashfs_sha256"];
        $vmFsList = ["combined_disk1-img_sha256", "combined_uefi1-img_sha256", "combined_disk-kvm-img_sha256"];

        $seenArches = [];

        $output = [];
        foreach ($products as $name => $product) {
            $imOs = null;
            $imRelease = null;
            $imArch = null;
            $imVariation = null;

            if ($isUbuntuRelaseServer) {
                extract($this->parseUbuntuSimpleStream($product));
            } else {
                extract($this->parseSimpleStreams($name, $product));
            }

            if (!in_array($imArch, $seenArches)) {
                $seenArches[] = $imArch;
            }


            if ($searchArch !== "" && $imArch !== $searchArch) {
                continue;
            }

            krsort($product["versions"]);

            $v = array_reverse($product["versions"]);

            $product = array_pop($v);

            if (!isset($product["items"]["lxd.tar.xz"])) {
                continue;
            }

            $lxdFolder = $product["items"]["lxd.tar.xz"];

            if ($searchType == "container") {
                $sList = $containerFsList;
            } else {
                $sList = $vmFsList;
            }

            foreach ($sList as $fingerKey) {
                if (isset($lxdFolder[$fingerKey])) {
                    if (!isset($output[$imOs])) {
                        $output[$imOs] = [];
                    }

                    $variant = "default";

                    if ($imVariation !== null) {
                        $variant = $imVariation;
                    }

                    if (!isset($output[$imOs][$variant])) {
                        $output[$imOs][$variant] = [];
                    }

                    $output[$imOs][$variant][$imRelease] = $lxdFolder[$fingerKey];
                }
            }
        }
        foreach ($output as &$os) {
            ksort($os);
        }
        return $output;
    }

    private function parseUbuntuSimpleStream($product)
    {
        return [
            "imOs" => $product["os"],
            "imRelease" => $product["release_title"],
            "imArch" => $product["arch"],
            "imVariation" => "default"
        ];
    }

    private function parseSimpleStreams($name, $product)
    {
        $nameParts = explode(":", $name);
        return [
            "imOs" => $nameParts[0],
            "imRelease" => $nameParts[1],
            "imArch" => $nameParts[2],
            "imVariation" => $nameParts[3]
        ];
    }

    public function getSimpleStreamsJson($url)
    {
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'LXDMosaic',
            CURLOPT_FOLLOWLOCATION => true
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return json_decode($resp, true);
    }
}

<?php

namespace dhope0000\LXDClient\Model\Images;

class GetLinuxContainersOrgImages
{
    public $lxcImagesUrl = "https://uk.images.linuxcontainers.org/";

    public function get($url = null)
    {
        if (empty($url)) {
            $url = $this->lxcImagesUrl;
        }
        $htmlContent = file_get_contents($url);
        $dom = new \DOMDocument();
        @$dom->loadHTML($htmlContent);

        $headers = $this->getHeaders($dom);
        $rows = $this->getRows($dom, $headers);

        return [
            "headers"=>$headers,
            "data"=>$rows
        ];
    }

    public function getHeaders($dom)
    {
        $header = $dom->getElementsByTagName('th');
        $textHeaders = [];
        foreach ($header as $nodeHeader) {
            $textHeaders[] = strtolower(trim($nodeHeader->textContent));
        }
        return $textHeaders;
    }

    public function getRows($dom, $headers)
    {
        $detail = $dom->getElementsByTagName('td');
        $textDetails = [];
        $i = 0;
        $z = 0;
        $headerCount = count($headers);

        foreach ($detail as $nodeDetail) {
            if ($z == $headerCount) {
                $i++;
                $z = 0;
            }
            $textDetails[$i][] = $nodeDetail->textContent;
            $z++;
        }

        return $textDetails;
    }
}

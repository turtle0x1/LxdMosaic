<?php
namespace dhope0000\LXDClient\Model\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;

class DeleteImages
{
    public function __construct(LxdClient $lxdClient)
    {
        $this->lxdClient = $lxdClient;
    }

    public function delete($imageData)
    {
        $output = [];
        foreach ($imageData as $image) {
            $this->validateOrThrowImageDetails($image);
            $client = $this->lxdClient->getClientByUrl($image["host"]);
            $output[] = $client->images->remove($image["fingerprint"]);
        }
        return $output;
    }

    private function validateOrThrowImageDetails(array $image)
    {
        if (!isset($image["host"]) && !is_string($image["host"])) {
            throw new \Exception("Host Details Missing", 1);
        } elseif (!isset($image["fingerprint"]) && !is_string($image["fingerprint"])) {
            throw new \Exception("Fingerprint Missing", 1);
        }
        return true;
    }
}

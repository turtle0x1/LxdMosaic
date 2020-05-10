<?php
namespace dhope0000\LXDClient\Tools\Images;

use dhope0000\LXDClient\Model\Client\LxdClient;

class UpdateImageProperties
{
    private $supportedProprties = [
        "public"=>"",
        "auto_update"=>""
    ];

    public function __construct(LxdClient $lxdClient)
    {
        $this->client = $lxdClient;
    }

    public function update(int $hostId, string $fingerprint, array $settings)
    {
        $newProps = array_intersect_key($settings, $this->supportedProprties);

        $client = $this->client->getANewClient($hostId);
        $details = $client->images->info($fingerprint);

        foreach ($newProps as $key => $value) {
            // Its late but it works
            if ($value === "false") {
                $value = false;
            } elseif ($value === "true") {
                $value = true;
            }
            $details[$key] = $value;
        }
        return $client->images->replace($fingerprint, $details);
    }
}

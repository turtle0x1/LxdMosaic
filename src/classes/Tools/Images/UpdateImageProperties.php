<?php
namespace dhope0000\LXDClient\Tools\Images;

class UpdateImageProperties
{
    private $supportedProprties = [
        "public"=>"",
        "auto_update"=>""
    ];

    public function update(Host $host, string $fingerprint, array $settings)
    {
        $newProps = array_intersect_key($settings, $this->supportedProprties);

        $details = $host->images->info($fingerprint);

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

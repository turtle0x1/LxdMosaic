<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\UpdateImageProperties;
use dhope0000\LXDClient\Objects\Host;

class UpdateImagePropertiesController
{
    private $updateImageProperties;
    
    public function __construct(UpdateImageProperties $updateImageProperties)
    {
        $this->updateImageProperties = $updateImageProperties;
    }

    /**
     * This is the list of proprties we support updating for an image
     */
    public function update(Host $host, string $fingerprint, array $settings)
    {
        $this->updateImageProperties->update($host, $fingerprint, $settings);
        return ["state"=>"success", "message"=>"Update image"];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\UpdateImageProperties;

class UpdateImagePropertiesController
{
    public function __construct(UpdateImageProperties $updateImageProperties)
    {
        $this->updateImageProperties = $updateImageProperties;
    }

    /**
     * This is the list of proprties we support updating for an image
     */
    public function update(int $hostId, string $fingerprint, array $settings)
    {
        $this->updateImageProperties->update($hostId, $fingerprint, $settings);
        return ["state"=>"success", "message"=>"Update image"];
    }
}

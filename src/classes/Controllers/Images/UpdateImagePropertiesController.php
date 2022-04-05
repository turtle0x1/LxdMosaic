<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\UpdateImageProperties;
use dhope0000\LXDClient\Objects\Host;
use Symfony\Component\Routing\Annotation\Route;

class UpdateImagePropertiesController
{
    public function __construct(UpdateImageProperties $updateImageProperties)
    {
        $this->updateImageProperties = $updateImageProperties;
    }
    /**
     * @Route("/api/Images/UpdateImagePropertiesController/update", methods={"POST"}, name="Update an images properties", options={"rbac" = "images.update"})
     */
    public function update(Host $host, string $fingerprint, array $settings)
    {
        $this->updateImageProperties->update($host, $fingerprint, $settings);
        return ["state"=>"success", "message"=>"Update image"];
    }
}

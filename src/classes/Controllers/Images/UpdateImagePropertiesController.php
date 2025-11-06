<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Images\UpdateImageProperties;
use Symfony\Component\Routing\Attribute\Route;

class UpdateImagePropertiesController
{
    public function __construct(
        private readonly UpdateImageProperties $updateImageProperties
    ) {
    }

    /**
     * This is the list of proprties we support updating for an image
     */
    #[Route(path: '/api/Images/UpdateImagePropertiesController/update', name: 'api_images_updateimagepropertiescontroller_update', methods: ['POST'])]
    public function update(Host $host, string $fingerprint, array $settings)
    {
        $this->updateImageProperties->update($host, $fingerprint, $settings);
        return [
            'state' => 'success',
            'message' => 'Update image',
        ];
    }
}

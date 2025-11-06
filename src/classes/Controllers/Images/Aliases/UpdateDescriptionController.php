<?php

namespace dhope0000\LXDClient\Controllers\Images\Aliases;

use dhope0000\LXDClient\Objects\Host;
use dhope0000\LXDClient\Tools\Images\Aliases\UpdateDescription;
use Symfony\Component\Routing\Attribute\Route;

class UpdateDescriptionController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly UpdateDescription $updateDescription
    ) {
    }

    #[Route(path: '/api/Images/Aliases/UpdateDescriptionController/update', name: 'Update Image Alias Description', methods: ['POST'])]
    public function update(Host $host, string $fingerprint, string $name, string $description = '')
    {
        $lxdResponse = $this->updateDescription->update($host, $fingerprint, $name, $description);
        return [
            'state' => 'success',
            'message' => 'Updated alias description',
            'lxdResponse' => $lxdResponse,
        ];
    }
}

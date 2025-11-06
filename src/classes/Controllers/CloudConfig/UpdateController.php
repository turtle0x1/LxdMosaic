<?php

namespace dhope0000\LXDClient\Controllers\CloudConfig;

use dhope0000\LXDClient\Tools\CloudConfig\Update;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly Update $update
    ) {
    }

    /**
     * @Route("/api/CloudConfig/UpdateController/update", name="Update Cloud Config", methods={"POST"})
     */
    public function update(int $cloudConfigId, string $code, array $imageDetails, array $envVariables = [])
    {
        $this->update->update($cloudConfigId, $code, $imageDetails, $envVariables);
        return [
            'state' => 'success',
            'message' => 'Save cloud config',
        ];
    }
}

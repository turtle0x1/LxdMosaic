<?php

namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Objects\HostsCollection;
use dhope0000\LXDClient\Tools\Images\ImportRemoteImagesByFingerprint;
use Symfony\Component\Routing\Attribute\Route;

class ImportRemoteImagesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(
        private readonly ImportRemoteImagesByFingerprint $importRemoteImagesByFingerprint
    ) {
    }

    #[Route(path: '/api/Images/ImportRemoteImagesController/import', name: 'Import image from simplestream server', methods: ['POST'])]
    public function import(HostsCollection $hosts, array $aliases, $urlKey)
    {
        $operations = $this->importRemoteImagesByFingerprint->import($hosts, $aliases, $urlKey);

        return [
            'state' => 'success',
            'message' => 'Image Import Started',
            'operations' => $operations,
        ];
    }
}

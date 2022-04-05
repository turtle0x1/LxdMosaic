<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\ImportRemoteImagesByFingerprint;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class ImportRemoteImagesController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(ImportRemoteImagesByFingerprint $importRemoteImagesByFingerprint)
    {
        $this->importRemoteImagesByFingerprint = $importRemoteImagesByFingerprint;
    }
    /**
     * @Route("/api/Images/ImportRemoteImagesController/import", methods={"POST"}, name="Import image from simplestream server", options={"rbac" = "images.import"})
     */
    public function import(HostsCollection $hosts, array $aliases, $urlKey)
    {
        $operations = $this->importRemoteImagesByFingerprint->import($hosts, $aliases, $urlKey);

        return [
            "state"=>"success",
            "message"=>"Image Import Started",
            "operations"=>$operations
        ];
    }
}

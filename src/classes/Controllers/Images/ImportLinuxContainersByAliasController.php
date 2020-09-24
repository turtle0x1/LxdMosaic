<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\ImportLinuxContainersByAlias;
use dhope0000\LXDClient\Objects\HostsCollection;
use Symfony\Component\Routing\Annotation\Route;

class ImportLinuxContainersByAliasController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(ImportLinuxContainersByAlias $importLinuxContainersByAlias)
    {
        $this->importLinuxContainersByAlias = $importLinuxContainersByAlias;
    }
    /**
     * @Route("", name="Import LinunxContainer.Org Image")
     */
    public function import(HostsCollection $hosts, array $aliases, $urlKey)
    {
        $operations = $this->importLinuxContainersByAlias->import($hosts, $aliases, $urlKey);

        return [
            "state"=>"success",
            "message"=>"Image Import Started",
            "operations"=>$operations
        ];
    }
}

<?php
namespace dhope0000\LXDClient\Controllers\Images;

use dhope0000\LXDClient\Tools\Images\ImportLinuxContainersByAlias;
use dhope0000\LXDClient\Objects\HostsCollection;

class ImportLinuxContainersByAliasController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(ImportLinuxContainersByAlias $importLinuxContainersByAlias)
    {
        $this->importLinuxContainersByAlias = $importLinuxContainersByAlias;
    }

    public function import(HostsCollection $hosts, array $aliases)
    {
        $operations = $this->importLinuxContainersByAlias->import($hosts, $aliases);

        return [
            "state"=>"success",
            "message"=>"Image Import Started",
            "operations"=>$operations
        ];
    }
}

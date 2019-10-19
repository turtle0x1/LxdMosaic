<?php
namespace dhope0000\LXDClient\Controllers\Hosts\Alias;

use dhope0000\LXDClient\Tools\Hosts\Alias\UpdateAlias;

class UpdateAliasController implements \dhope0000\LXDClient\Interfaces\RecordAction
{
    public function __construct(UpdateAlias $updateAlias)
    {
        $this->updateAlias = $updateAlias;
    }

    public function update(int $hostId, string $alias)
    {
        $this->updateAlias->update($hostId, $alias);
        return ["state"=>"success", "messages"=>"Updated Alias"];
    }
}

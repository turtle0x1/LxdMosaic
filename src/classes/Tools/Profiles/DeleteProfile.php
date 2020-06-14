<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Objects\Host;

class DeleteProfile
{
    public function delete(Host $host, string $profile)
    {
        return $host->profiles->remove($profile);
    }
}

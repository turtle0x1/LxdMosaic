<?php
namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Model\Client\LxdClient;
use dhope0000\LXDClient\Tools\Utilities\StringTools;
use dhope0000\LXDClient\Model\Hosts\GetDetails;

class GetProfile
{
    public function __construct(LxdClient $lxdClient, GetDetails $getDetails)
    {
        $this->lxdClient = $lxdClient;
        $this->getDetails = $getDetails;
    }

    public function get(int $hostId, string $profile)
    {
        $client = $this->lxdClient->getANewClient($hostId);
        $profile =  $client->profiles->info($profile);

        $profile["used_by"] = StringTools::usedByStringsToLinks(
            $hostId,
            $profile["used_by"],
            $this->getDetails->fetchAlias($hostId)
        );

        return $profile;
    }
}

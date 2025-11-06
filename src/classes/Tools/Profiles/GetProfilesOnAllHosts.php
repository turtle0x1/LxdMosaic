<?php

namespace dhope0000\LXDClient\Tools\Profiles;

use dhope0000\LXDClient\Tools\Universe;

class GetProfilesOnAllHosts
{
    public function __construct(
        private readonly Universe $universe
    ) {
    }

    public function getProfilesOnAllHosts(int $userId, string $search)
    {
        $clustersAndHosts = $this->universe->getEntitiesUserHasAccesTo($userId, 'profiles');

        $totalHosts = 0;
        $profiles = [];

        foreach ($clustersAndHosts['clusters'] as $clusterIndex => $cluster) {
            foreach ($cluster['members'] as $hostIndex => &$host) {
                if ($host->hostOnline() == false) {
                    continue;
                }
                $totalHosts++;
                foreach ($host->getCustomProp('profiles') as $profile) {
                    if (!isset($profiles[$profile])) {
                        $profiles[$profile] = 0;
                    }
                    $profiles[$profile]++;
                }
            }
        }

        foreach ($clustersAndHosts['standalone']['members'] as $host) {
            if ($host->hostOnline() == false) {
                continue;
            }
            $totalHosts++;
            foreach ($host->getCustomProp('profiles') as $profile) {
                if (!isset($profiles[$profile])) {
                    $profiles[$profile] = 0;
                }
                $profiles[$profile]++;
            }
        }

        $output = [];

        foreach ($profiles as $profile => $count) {
            if ($count == $totalHosts && stripos($profile, $search) !== false) {
                $output[] = [
                    'profile' => $profile,
                ];
            }
        }

        return $output;
    }
}

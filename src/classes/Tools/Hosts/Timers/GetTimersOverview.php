<?php

namespace dhope0000\LXDClient\Tools\Hosts\Timers;

use dhope0000\LXDClient\Model\Hosts\GetDetails;
use dhope0000\LXDClient\Model\Hosts\Timers\FetchTimersSnapshot;

class GetTimersOverview
{
    public function __construct(
        private readonly FetchTimersSnapshot $fetchTimersSnapshot,
        private readonly GetDetails $getDetails
    ) {
    }

    public function get(?\DateTimeImmutable $date = null)
    {
        if ($date == null) {
            $date = new \DateTimeImmutable();
        }

        $snapshot = $this->fetchTimersSnapshot->fetchForDate($date);
        $snapData = json_decode((string) $snapshot['data'], true);

        if (empty($snapData)) {
            return [];
        }

        $hostAliases = $this->getDetails->fetchAliases(array_keys($snapData));

        $uniqueCommands = [];
        foreach ($snapData as $hostId => $projects) {
            foreach ($projects as $project => $instances) {
                foreach ($instances as $instance => $jobs) {
                    foreach ($jobs as $job) {
                        foreach ($job as $command) {
                            if (!isset($uniqueCommands[$command['command']])) {
                                $uniqueCommands[$command['command']] = [
                                    'command' => $command['command'],
                                    'schedules' => [],
                                    'count' => 0,
                                    'instances' => [],
                                ];
                            }
                            $uniqueCommands[$command['command']]['count']++;
                            $uniqueCommands[$command['command']]['instances'][] = [
                                'hostId' => $hostId,
                                'hostAlias' => $hostAliases[$hostId],
                                'project' => $project,
                                'instance' => $instance,
                            ];
                            if (!in_array($command['pattern'], $uniqueCommands[$command['command']]['schedules'])) {
                                $uniqueCommands[$command['command']]['schedules'][] = $command['pattern'];
                            }
                        }
                    }
                }
            }
        }

        $uniqueCommands = array_values($uniqueCommands);
        usort($uniqueCommands, fn ($a, $b) => $a['count'] > $b['count'] ? 1 : -1);

        return [
            'uniqueCommands' => $uniqueCommands,
        ];
    }
}

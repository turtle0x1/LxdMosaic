<?php

namespace dhope0000\LXDClient\Tools\Search;

use dhope0000\LXDClient\Model\Search\Index\FetchIndex;
use dhope0000\LXDClient\Model\Users\AllowedProjects\FetchAllowedProjects;
use dhope0000\LXDClient\Model\Users\FetchUserDetails;

class SearchIndex
{
    public function __construct(
        private readonly FetchIndex $fetchIndex,
        private readonly FetchAllowedProjects $fetchAllowedProjects,
        private readonly FetchUserDetails $fetchUserDetails
    ) {
    }

    public function search(string $userId, string $search)
    {
        $searchData = $this->fetchIndex->fetchLatestData();
        if ($searchData == false) {
            throw new \Exception('Search index missing');
        }
        $index = json_decode((string) $searchData, true);
        $entityIndex = $index['entityIndex'];
        $index = $index['dataIndex'];

        $searchParts = explode(' ', $search);
        $results = [];

        foreach ($searchParts as $searchPart) {
            // TODO check if searching properties split by ":"
            foreach ($index as $indexKey => $usedBy) {
                if (is_numeric(implode('', array_keys($usedBy)))) {
                    $this->checkMatch($searchPart, $indexKey, $usedBy, $results, $entityIndex);
                } else {
                    foreach ($usedBy as $subIndexKey => $xyz) {
                        $this->checkMatch($searchPart, $subIndexKey, $xyz, $results, $entityIndex);
                    }
                }
            }
        }

        foreach ($results as $index => $xyz) {
            if (count($xyz['searchMatches']) >= count($searchParts)) {
                continue;
            }
            unset($results[$index]);
        }

        $results = $this->formatResults($results);
        if ($this->fetchUserDetails->isAdmin($userId)) {
            return $results;
        }
        return $this->filterResults($userId, $results);
    }

    private function filterResults($userId, $results)
    {
        $allowedProjects = $this->fetchAllowedProjects->fetchAll($userId);
        $output = [];

        foreach ($results as $result) {
            if (isset($allowedProjects[$result['hostId']]) && in_array(
                $result['project'],
                $allowedProjects[$result['hostId']]
            )) {
                $output[] = $result;
            }
        }
        return $output;
    }

    private function formatResults($results)
    {
        $output = [];
        foreach ($results as $item => $matches) {
            $parts = explode(',', (string) $item);
            $output[] = [
                'hostId' => $parts[0],
                'project' => $parts[1],
                'entity' => $parts[2],
                'name' => $parts[3],
                'matches' => $matches,
            ];
        }
        usort($output, fn ($a, $b) => $a['entity'] > $b['entity'] ? 1 : -1);
        return $output;
    }

    private function checkMatch($search, $haystack, array $toExtract, &$results, $entityIndex)
    {
        if (str_contains((string) $haystack, (string) $search)) {
            foreach ($toExtract as $entity) {
                if (!isset($entityIndex[$entity])) {
                    continue; // TODO Really bad this shouldn't happen
                }

                $y = $entityIndex[$entity];
                $x = str_replace('[', '', $y);
                $parts = explode(']', $x);
                $entityName = implode(',', array_slice($parts, 0, 4));
                $entityProp = implode('.', array_slice($parts, 4, count($parts)));
                // $entityName = $x;

                if (!isset($results[$entityName])) {
                    $results[$entityName] = [
                        'searchMatches' => [],
                        'matches' => [],
                    ];
                }
                if (!isset($results[$entityName]['searchMatches'][$search])) {
                    $results[$entityName]['searchMatches'][$search] = 0;
                }
                $results[$entityName]['searchMatches'][$search]++;
                $results[$entityName]['matches'][$entityProp] = $haystack;
            }
        }
    }
}

<?php

namespace dhope0000\LXDClient\Controllers\Search;

use Symfony\Component\Routing\Annotation\Route;

class SearchIndexController
{
    /**
     * @Route("/api/search/fuzzy", methods={"POST"}, name="Fuzzy Search")
     */
    public function get(string $search)
    {
        // NOTE So this takes over 1 second on somewhere between 4000-8000 hosts
        $index = json_decode(file_get_contents(__DIR__ . "/../../../../lxdex/index.json"), true);

        $entityIndex = $index["entityIndex"];
        $index = $index["dataIndex"];

        $searchParts = explode(" ", $search);
        $results = [];

        foreach ($searchParts as $searchPart) {
            // TODO check if searching properties split by ":"
            foreach ($index as $indexKey => $usedBy) {
                if (is_numeric(implode(array_keys($usedBy)))) {
                    $this->checkMatch($searchPart, $indexKey, $usedBy, $results, $entityIndex);
                } else {
                    foreach ($usedBy as $subIndexKey=>$xyz) {
                        $this->checkMatch($searchPart, $subIndexKey, $xyz, $results, $entityIndex);
                    }
                }
            }
        }

        foreach ($results as $index => $xyz) {
            if (count($xyz["searchMatches"]) >= count($searchParts)) {
                continue;
            }
            unset($results[$index]);
        }
        //TODO Filter results by user access
        return $this->formatResults($results);
    }

    private function formatResults($results)
    {
        $output = [];
        foreach ($results as $item => $matches) {
            $parts = explode(",", $item);
            $output[] = [
                "hostId"=>$parts[0],
                "project"=>$parts[1],
                "entity"=>$parts[2],
                "name"=>$parts[3],
                "matches"=>$matches
            ];
        }
        usort($output, function ($a, $b) {
            return $a["entity"] > $b["entity"] ? 1 : -1;
        });
        return $output;
    }


    private function checkMatch($search, $haystack, array $toExtract, &$results, $entityIndex)
    {
        if (strpos($haystack, $search) !== false) {
            foreach ($toExtract as $entity) {
                if (!isset($entityIndex[$entity])) {
                    continue; // TODO Really bad this shouldn't happen
                }
                $y = $entityIndex[$entity];
                $x = str_replace("[", "", $y);
                $parts = explode("]", $x);
                $entityName = implode(array_slice($parts, 0, 4), ",");
                $entityProp = implode(array_slice($parts, 4, count($parts)), ",");
                // $entityName = $x;

                if (!isset($results[$entityName])) {
                    $results[$entityName] = [
                        "searchMatches"=>[],
                        "matches"=>[]
                    ];
                }
                if (!isset($results[$entityName]["searchMatches"][$search])) {
                    $results[$entityName]["searchMatches"][$search] = 0;
                }
                $results[$entityName]["searchMatches"][$search]++;
                $results[$entityName]["matches"][$entityProp] = $haystack;
            }
        }
    }
}

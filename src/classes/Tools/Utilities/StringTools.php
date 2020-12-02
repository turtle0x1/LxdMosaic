<?php

namespace dhope0000\LXDClient\Tools\Utilities;

use dhope0000\LXDClient\Objects\Host;

class StringTools
{
    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    public static function random($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    /**
     * https://stackoverflow.com/a/2790919/4008082
     */
    public function stringStartsWith(string $string, string $query)
    {
        return substr($string, 0, strlen($query)) === $query;
    }

    /**
     * https://stackoverflow.com/a/9826656/4008082
     */
    public static function getStringBetween(string $string, string $start, string $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function usedByStringsToLinks(Host $host, array $usedBy)
    {
        foreach ($usedBy as $index => $item) {
            $parts = parse_url($item);

            $query = [];

            if (isset($parts["query"])) {
                parse_str($parts['query'], $query);
            }

            $explodedPath = explode("/", $parts["path"]);

            $lastItem = array_pop($explodedPath);

            $project = "";

            if (isset($query["project"])) {
                $project = $query["project"];
            }

            $icon = "";
            $class = "";
            $data = " data-project='$project' data-host-id='{$host->getHostId()}' data-alias='{$host->getAlias()}' ";

            if (strpos($item, '/snapshots/') !== false) {
                $container = $explodedPath[count($explodedPath) - 2];
                $lastItem =  $container . "/" . $lastItem;
                $data .= "data-container='$container' ";
                $class = 'goToInstance';
                $icon = "camera";
            } elseif (strpos($item, '/instances/') !== false || strpos($item, '/containers/') !== false) {
                $data .= "data-container='$lastItem' ";
                $class = 'goToInstance';
                $icon = "box";
            } elseif (strpos($item, '/profiles/') !== false) {
                $class = 'toProfile';
                $data .= "data-profile='$lastItem'";
                $icon = "users";
            } elseif (strpos($item, '/images/') !== false) {
                $host->setProject($project);
                $imageDetails = $host->images->info($lastItem);

                $lastItem = $imageDetails["properties"]["os"] . " " . $imageDetails["properties"]["release"] . "({$imageDetails['type']})";

                if (!empty($imageDetails["aliases"])) {
                    $lastItem = implode(",", array_column($imageDetails["aliases"], "name")) . "({$imageDetails['type']})";
                }

                $class = 'viewImages';
                $icon = "images";
            } elseif (strpos($item, '/volumes/') !== false) {
                $icon = "database";
            }

            $item = "<a href='#' class='$class' $data>
                <i class='fas fa-$icon'></i>
                $lastItem
            </a>";

            $usedBy[$index] = $item;
        }
        return $usedBy;
    }
}

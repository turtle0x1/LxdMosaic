<?php

namespace dhope0000\LXDClient\Tools\Utilities;

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

    public static function usedByStringsToLinks(
        int $hostId,
        array $usedBy,
        string $hostAlias = "",
        $client = null
    ) {
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
            $data = " data-project='$project' data-host-id='$hostId' data-alias='$hostAlias' ";


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
                if ($client !== null) {
                    $imageDetails = $client->images->info($lastItem);

                    $lastItem = $imageDetails["properties"]["os"] . " " . $imageDetails["properties"]["release"] . "({$imageDetails['type']})";

                    if (!empty($imageDetails["aliases"])) {
                        $lastItem = implode(",", array_column($imageDetails["aliases"], "name")) . "({$imageDetails['type']})";
                    }
                }

                $class = 'viewImages';
                $icon = "images";
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

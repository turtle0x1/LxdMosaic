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
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    /**
     * https://stackoverflow.com/a/2790919/4008082
     */
    public function stringStartsWith(string $string, string $query)
    {
        return str_starts_with($string, $query);
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
            $parts = parse_url((string) $item);

            $query = [];

            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);
            }

            $explodedPath = explode('/', $parts['path']);

            $lastItem = array_pop($explodedPath);

            $itemProject = 'default';

            if (isset($query['project'])) {
                $itemProject = $query['project'];
            }

            $icon = '';
            $class = '';
            $href = '';
            $tag = 'a';

            if (str_contains((string) $item, '/snapshots/')) {
                $container = $explodedPath[count($explodedPath) - 2];
                $lastItem = $container . '/' . $lastItem;
                $href = "/instance/{$host->getHostId()}/{$container}";
                $icon = 'camera';
            } elseif (str_contains((string) $item, '/instances/') || str_contains((string) $item, '/containers/')) {
                $icon = 'box';
                $href = "/instance/{$host->getHostId()}/{$lastItem}";
            } elseif (str_contains((string) $item, '/profiles/')) {
                $icon = 'users';
                $href = "/profiles/{$host->getHostId()}/{$lastItem}";
            } elseif (str_contains((string) $item, '/images/')) {
                $origProject = $host->getProject();
                $host->setProject($itemProject);
                $imageDetails = $host->images->info($lastItem);
                $host->setProject($origProject);

                $lastItem = $imageDetails['properties']['os'] . ' ' . $imageDetails['properties']['release'] . "({$imageDetails['type']})";

                if (!empty($imageDetails['aliases'])) {
                    $lastItem = implode(
                        ',',
                        array_column($imageDetails['aliases'], 'name')
                    ) . "({$imageDetails['type']})";
                }

                $icon = 'images';

                $href = "/images/{$host->getHostId()}/{$imageDetails['fingerprint']}";
            } elseif (str_contains((string) $item, '/volumes/')) {
                $tag = 'span';
                $class = 'disabled';
                $icon = 'database';
            }

            // Currently the frontend can't handle jumping to items in a project
            // the user isn't currently in so we have to make these non clickable
            // for the time being
            if ($itemProject !== $host->getProject()) {
                $tag = 'span';
            }

            $item = "<{$tag} href='{$href}?project={$itemProject}' class='{$class}' data-navigo>
                <i class='fas fa-{$icon} pe-1'></i>
                {$lastItem}
                <i class='fas fa-project-diagram ps-2 pe-1'></i> {$itemProject}
            </{$tag}>";

            $usedBy[$index] = $item;
        }
        return $usedBy;
    }
}

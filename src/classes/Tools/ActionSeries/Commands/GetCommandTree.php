<?php

namespace dhope0000\LXDClient\Tools\ActionSeries\Commands;

use dhope0000\LXDClient\Model\ActionSeries\Commands\FetchCommands;

class GetCommandTree
{
    public function __construct(FetchCommands $fetchCommands)
    {
        $this->fetchCommands = $fetchCommands;
    }

    // straight from https://stackoverflow.com/a/10332361/4008082
    public function get(int $actionSeries)
    {
        $commands = $this->fetchCommands->fetchForSeries($actionSeries);
        $new = [];
        foreach ($commands as $a) {
            $new[$a['parentId']][] = $a;
        }
        return $this->createTree($new, $new[0]);
    }

    // straight from https://stackoverflow.com/a/10332361/4008082
    public function createTree(&$list, $parent)
    {
        $tree = [];
        foreach ($parent as $k=>$l) {
            if (isset($list[$l['id']])) {
                $l['children'] = $this->createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }
}

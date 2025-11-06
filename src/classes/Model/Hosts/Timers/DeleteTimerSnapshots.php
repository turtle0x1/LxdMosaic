<?php

namespace dhope0000\LXDClient\Model\Hosts\Timers;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteTimerSnapshots
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteBefore(\DateTimeInterface $before)
    {
        $sql = 'DELETE FROM
                    `Timers_Snapshots`
                WHERE
                    `TS_Date` <= :beforeDate
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':beforeDate' => $before->format('Y-m-d H:i:s'),
        ]);
        return $do->rowCount() ? true : false;
    }
}

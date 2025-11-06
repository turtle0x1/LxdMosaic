<?php

namespace dhope0000\LXDClient\Model\Hosts\Settings;

use dhope0000\LXDClient\Model\Database\Database;

class SetHostSettings
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function set(int $hostId, string $alias, int $supportsLoadAverages)
    {
        $sql = 'UPDATE
                    `Hosts`
                SET
                    `Host_Alias` = :alias,
                    `Host_Support_Load_Averages` = :supportsLoadAvgs

                WHERE
                    `Host_ID` = :hostId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':alias' => $alias,
            ':hostId' => $hostId,
            ':supportsLoadAvgs' => $supportsLoadAverages,
        ]);
        return $do->rowCount() ? true : false;
    }
}

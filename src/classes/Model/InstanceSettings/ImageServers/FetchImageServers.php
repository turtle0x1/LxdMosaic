<?php

namespace dhope0000\LXDClient\Model\InstanceSettings\ImageServers;

use dhope0000\LXDClient\Model\Database\Database;

class FetchImageServers
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function fetchAll()
    {
        $sql = 'SELECT
                    `IS_Date_Created` as `created`,
                    `IS_Alias` as `alias`,
                    `IS_Url` as `url`
                FROM
                    `Image_Servers`
                ORDER BY
                    `IS_Alias` ASC
                ';
        return $this->database->query($sql)
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetchAllAliases()
    {
        $sql = 'SELECT
                    `IS_Alias`
                FROM
                    `Image_Servers`
                ORDER BY
                    `IS_Alias` ASC
                ';
        return $this->database->query($sql)
            ->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    public function fetchAliasDetails(string $alias)
    {
        $sql = 'SELECT
                    `IS_Url` as `url`,
                    `IS_Protocol` `protocol`
                FROM
                    `Image_Servers`
                WHERE
                    `IS_Alias` = :alias
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':alias' => $alias,
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }
}

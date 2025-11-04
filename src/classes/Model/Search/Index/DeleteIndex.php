<?php

namespace dhope0000\LXDClient\Model\Search\Index;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteIndex
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function deleteWhereIdNot(int $toKeepIndex)
    {
        $sql = "DELETE FROM `Search_Index` WHERE SI_ID <> :toKeep";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":toKeep" => $toKeepIndex
        ]);
        return $do->rowCount();
    }
}

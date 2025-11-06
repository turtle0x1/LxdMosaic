<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class RemoveToken
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function remove(int $tokenId)
    {
        $sql = 'DELETE FROM
                    `User_Api_Tokens`
                WHERE
                    `UAT_ID` = :tokenId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':tokenId' => $tokenId,
        ]);
        return $do->rowCount() ? true : false;
    }
}

<?php

namespace dhope0000\LXDClient\Model\Users;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateToken
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function update(int $userId)
    {
        $sql = "UPDATE
                    ws_tokens
                SET
                    used = NOW()
		WHERE
                    used IS NULL
                AND
                    user_id = :user_id";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":user_id"=>$userId
        ]);
        return $do->rowCount() ? true : false;
    }
}

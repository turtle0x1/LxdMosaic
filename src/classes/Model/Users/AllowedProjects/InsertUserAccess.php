<?php

namespace dhope0000\LXDClient\Model\Users\AllowedProjects;

use dhope0000\LXDClient\Model\Database\Database;

class InsertUserAccess
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function insert(int $grantedBy, int $userId, int $hostId, string $project)
    {
        $sql = "INSERT INTO `User_Allowed_Projects`(
                    `UAP_Granted_By`,
                    `UAP_User_ID`,
                    `UAP_Host_ID`,
                    `UAP_Project`
                ) VALUES (
                    :grantedBy,
                    :userId,
                    :hostId,
                    :project
                );";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":grantedBy"=>$grantedBy,
            ":userId"=>$userId,
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->rowCount() ?  true : false;
    }
}

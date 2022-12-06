<?php

namespace dhope0000\LXDClient\Model\Users\AllowedProjects;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteUserAccess
{
    private \PDO $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $userId, int $hostId, string $project) :bool
    {
        $sql = "DELETE FROM `User_Allowed_Projects`
                WHERE
                    `UAP_User_ID` = :userId
                AND
                    `UAP_Host_ID` = :hostId
                AND
                    `UAP_Project` = :project

                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deletAllForProject(int $hostId, string $project) :bool
    {
        $sql = "DELETE FROM `User_Allowed_Projects`
                WHERE
                    `UAP_Host_ID` = :hostId
                AND
                    `UAP_Project` = :project

                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteForHost(int $hostId) :bool
    {
        $sql = "DELETE FROM `User_Allowed_Projects`
                WHERE
                    `UAP_Host_ID` = :hostId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":hostId"=>$hostId
        ]);
        return $do->rowCount() ? true : false;
    }
    public function deleteForUser(int $targetUserId) :bool
    {
        $sql = "DELETE FROM `User_Allowed_Projects`
                WHERE
                    `UAP_User_ID` = :targetUserId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":targetUserId"=>$targetUserId
        ]);
        return $do->rowCount() ? true : false;
    }
}

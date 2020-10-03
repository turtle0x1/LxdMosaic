<?php

namespace dhope0000\LXDClient\Model\Users\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class InsertUserProject
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function putUsersOnProjectToProject(int $hostId, string $oldProject, string $newProject)
    {
        $sql = "UPDATE
                    `User_Host_Projects`
                SET
                    `UHP_Project` = :newProject
                WHERE
                    `UHP_Host_ID` = :hostId
                AND
                    `UHP_Project` = :oldProject
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":newProject"=>$newProject,
            ":hostId"=>$hostId,
            ":oldProject"=>$oldProject
        ]);
        return $do->rowCount() ? true : false;
    }

    public function insert(int $userId, int $hostId, string $project)
    {
        $sql = "INSERT INTO `User_Host_Projects`(
                    `UHP_User_ID`,
                    `UHP_Host_ID`,
                    `UHP_Project`
                ) VALUES (
                    :userId,
                    :hostId,
                    :project
                ) ON DUPLICATE KEY UPDATE
                    `UHP_Project` = :project
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":userId"=>$userId,
            ":hostId"=>$hostId,
            ":project"=>$project
        ]);
        return $do->rowCount() ? true : false;
    }
}

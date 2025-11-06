<?php

namespace dhope0000\LXDClient\Model\Users\Projects;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteUserProject
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function removeFromProject(int $userId, int $hostId, string $project)
    {
        $sql = 'DELETE FROM
                    `User_Host_Projects`
                WHERE
                    `UHP_User_ID` = :userId
                AND
                    `UHP_Host_ID` = :hostId
                AND
                    `UHP_Project` = :project
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':userId' => $userId,
            ':hostId' => $hostId,
            ':project' => $project,
        ]);
        return $do->rowCount() ? true : false;
    }

    public function deleteForHost(int $hostId)
    {
        $sql = 'DELETE FROM
                    `User_Host_Projects`
                WHERE
                    `UHP_Host_ID` = :hostId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
        ]);
        return $do->rowCount() ? true : false;
    }

    public function removeAllUsersFromProject(int $hostId, string $project)
    {
        $sql = 'DELETE FROM
                    `User_Host_Projects`
                WHERE
                    `UHP_Host_ID` = :hostId
                AND
                    `UHP_Project` = :project
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
            ':project' => $project,
        ]);
        return $do->rowCount() ? true : false;
    }
}

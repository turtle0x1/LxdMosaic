<?php

namespace dhope0000\LXDClient\Model\Hosts;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateHost
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function updateCertificateDetails(int $hostId, string $keyPath, string $certPath, string $combinedPath)
    {
        $sql = 'UPDATE `Hosts` SET
                    `Host_Key_File` = :keyPath,
                    `Host_Cert_Only_File` = :certPath,
                    `Host_Cert_Path` = :combinedPath
                WHERE
                    `Host_ID` = :hostId
                ';
        $do = $this->database->prepare($sql);
        $do->execute([
            ':hostId' => $hostId,
            ':keyPath' => $keyPath,
            ':certPath' => $certPath,
            ':combinedPath' => $combinedPath,
        ]);
        return $do->rowCount() ? true : false;
    }
}

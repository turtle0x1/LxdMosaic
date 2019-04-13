<?php
namespace dhope0000\LXDClient\Model\CloudConfig;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteCloudConfig
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function delete(int $cloudConfigId)
    {
        $sql = "DELETE FROM
                    `Cloud_Config`
                WHERE
                    `CC_ID` = :cloudConfigId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigId"=>$cloudConfigId
        ]);
        return $do->rowCount() ? true : false;
    }
}

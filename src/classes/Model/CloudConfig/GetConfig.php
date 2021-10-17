<?php
namespace dhope0000\LXDClient\Model\CloudConfig;

use dhope0000\LXDClient\Model\Database\Database;

class GetConfig
{
    public function __construct(Database $database)
    {
        $this->database = $database->dbObject;
    }

    public function getHeader(int $cloudConfigId)
    {
        $sql = "SELECT
                    `CC_ID` as `id`,
                    `CC_Name` as `name`,
                    `CC_Namespace` as `namespace`
                FROM
                    `Cloud_Config`
                WHERE
                    `CC_ID` = :cloudConfigId
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigId"=>$cloudConfigId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function getLatestConfig(int $cloudConfigId)
    {
        $sql = "SELECT
                    `CCD_ID` as `revisionId`,
                    `CCD_Cloud_Config_ID` as `cloudConfigId`,
                    `CCD_Data` as `data`,
                    `CCD_Image_Details` as `imageDetails`,
                    `CCD_Enviroment_Variables` as `envVariables`
                FROM
                    `Cloud_Config_Data`
                WHERE
                    `CCD_Cloud_Config_ID` = :cloudConfigId
                ORDER BY
                    `CCD_ID` DESC
                LIMIT 1;
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigId"=>$cloudConfigId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function getLatestConfigByRevId(int $cloudConfigRevId)
    {
        $sql = "SELECT
                    `CCD_ID` as `revisionId`,
                    `CCD_Cloud_Config_ID` as `cloudConfigId`,
                    `CCD_Data` as `data`,
                    `CCD_Image_Details` as `imageDetails`,
                    `CCD_Enviroment_Variables` as `envVariables`
                FROM
                    `Cloud_Config_Data`
                WHERE
                    `CCD_ID` = :cloudConfigRevId
                ORDER BY
                    `CCD_ID` DESC
                ";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":cloudConfigRevId"=>$cloudConfigRevId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCloudConfigByRevId(int $revId)
    {
        $sql = "SELECT
                    `CC_ID` as `id`,
                    `CC_Name` as `name`,
                    `CC_Namespace` as `namespace`
                FROM
                    `Cloud_Config_Data`
                INNER JOIN `Cloud_Config` ON
                    `Cloud_Config`.`CC_ID` = `Cloud_Config_Data`.`CCD_Cloud_Config_ID`
                WHERE
                    `CCD_ID` = :revId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":revId"=>$revId
        ]);
        return $do->fetch(\PDO::FETCH_ASSOC);
    }

    public function getImageDetailsByRevId(int $revId)
    {
        $sql = "SELECT
                    `CCD_Image_Details`
                FROM
                    `Cloud_Config_Data`
                WHERE
                    `CCD_ID` = :revId";
        $do = $this->database->prepare($sql);
        $do->execute([
            ":revId"=>$revId
        ]);
        return $do->fetchColumn();
    }
}

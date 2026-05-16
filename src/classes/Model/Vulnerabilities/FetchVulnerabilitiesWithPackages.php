<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities;

use dhope0000\LXDClient\Model\Database\Database;

class FetchVulnerabilitiesWithPackages
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function fetchAll(): array
    {
        $stmt = $this->db->query(
            "SELECT
                v.`V_ID`,
                os.`OS_Name`          AS `os_name`,
                os.`OS_Version`       AS `os_version`,
                vp.`VP_Package_Name`  AS `pkg_name`,
                vp.`VP_Fixed_Version` AS `fixed_version`
             FROM `Vulnerabilities` v
             INNER JOIN `Operating_Systems` os ON os.`OS_ID` = v.`V_OS_ID`
             LEFT JOIN `Vulnerable_Packages` vp ON vp.`VP_V_ID` = v.`V_ID`
             ORDER BY v.`V_ID`"
        );

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

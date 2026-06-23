<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Cve;

use dhope0000\LXDClient\Model\Database\Database;

class UpdateCve
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function update(
        string $cveId,
        int $vId,
        ?string $priority,
        ?float $cvssScore,
        ?string $cvssSeverity,
        ?string $cvssVector,
        ?string $publicDate
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE `CVEs` SET
                `C_Priority` = :priority,
                `C_CVSS_Score` = :cvss_score,
                `C_CVSS_Severity` = :cvss_severity,
                `C_CVSS_Vector` = :cvss_vector,
                `C_Public_Date` = :public_date
             WHERE `C_CVE_Id` = :cve_id AND `C_V_ID` = :v_id"
        );
        $stmt->execute([
            ':cve_id'       => $cveId,
            ':v_id'         => $vId,
            ':priority'      => $priority,
            ':cvss_score'    => $cvssScore,
            ':cvss_severity' => $cvssSeverity,
            ':cvss_vector'   => $cvssVector,
            ':public_date'   => $publicDate,
        ]);

        return $stmt->rowCount() > 0;
    }
}

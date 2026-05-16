<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Cve;

use dhope0000\LXDClient\Model\Database\Database;

class InsertCve
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function insert(
        string $cveId,
        int $vId,
        ?string $priority,
        ?float $cvssScore,
        ?string $cvssSeverity,
        ?string $cvssVector,
        ?string $publicDate
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO `CVEs`
                (`C_CVE_Id`, `C_V_ID`, `C_Priority`, `C_CVSS_Score`, `C_CVSS_Severity`, `C_CVSS_Vector`, `C_Public_Date`)
             VALUES (:cve_id, :v_id, :priority, :cvss_score, :cvss_severity, :cvss_vector, :public_date)"
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

        return (int) $this->db->lastInsertId();
    }
}

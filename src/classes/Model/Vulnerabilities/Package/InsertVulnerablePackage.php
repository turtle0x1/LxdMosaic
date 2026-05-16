<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Package;

use dhope0000\LXDClient\Model\Database\Database;

class InsertVulnerablePackage
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function insert(string $packageName, int $vId, ?string $fixedVersion): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `Vulnerable_Packages` (`VP_Package_Name`, `VP_Fixed_Version`, `VP_V_ID`)
             VALUES (:pkg, :ver, :v_id)"
        );
        $stmt->execute([
            ':pkg' => $packageName,
            ':ver' => $fixedVersion,
            ':v_id' => $vId,
        ]);

        return (int) $this->db->lastInsertId();
    }
}

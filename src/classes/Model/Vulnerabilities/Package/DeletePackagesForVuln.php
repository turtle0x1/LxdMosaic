<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Package;

use dhope0000\LXDClient\Model\Database\Database;

class DeletePackagesForVuln
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function delete(int $vId): int
    {
        $stmt = $this->db->prepare("DELETE FROM `Vulnerable_Packages` WHERE `VP_V_ID` = :v_id");
        $stmt->execute([':v_id' => $vId]);
        return (int) $stmt->rowCount();
    }
}

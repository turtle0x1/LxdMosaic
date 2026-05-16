<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Cve;

use dhope0000\LXDClient\Model\Database\Database;

class DeleteCvesByIds
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function delete(int $vId, array $cveIds): int
    {
        $placeholders = implode(',', array_fill(0, count($cveIds), '?'));
        $stmt = $this->db->prepare("DELETE FROM `CVEs` WHERE `C_V_ID` = :v_id AND `C_CVE_Id` IN ({$placeholders})");
        $stmt->execute(array_merge([$vId], $cveIds));
        return (int) $stmt->rowCount();
    }
}

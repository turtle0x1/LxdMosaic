<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Cve;

use dhope0000\LXDClient\Model\Database\Database;

class FetchCveIdsByVuln
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function fetch(int $vId): array
    {
        $stmt = $this->db->prepare("SELECT `C_CVE_Id` FROM `CVEs` WHERE `C_V_ID` = :v_id");
        $stmt->execute([':v_id' => $vId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}

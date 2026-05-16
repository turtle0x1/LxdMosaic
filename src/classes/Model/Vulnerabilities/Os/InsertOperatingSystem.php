<?php

namespace dhope0000\LXDClient\Model\Vulnerabilities\Os;

use dhope0000\LXDClient\Model\Database\Database;

class InsertOperatingSystem
{
    private \PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->dbObject;
    }

    public function insert(string $name, string $version, ?string $codename): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO `Operating_Systems` (`OS_Name`, `OS_Version`, `OS_Codename`)
             VALUES (:name, :version, :codename)
             ON DUPLICATE KEY UPDATE `OS_Codename` = VALUES(`OS_Codename`)"
        );
        $stmt->execute([
            ':name'     => strtolower($name),
            ':version'  => $version,
            ':codename' => $codename,
        ]);

        $stmt = $this->db->prepare(
            "SELECT `OS_ID` FROM `Operating_Systems` WHERE `OS_Name` = :name AND `OS_Version` = :version"
        );
        $stmt->execute([':name' => strtolower($name), ':version' => $version]);

        return (int) $stmt->fetchColumn();
    }
}

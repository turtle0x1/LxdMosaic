<?php

namespace dhope0000\LXDClient\Model\Database;

class Database
{
    public function __construct()
    {
        if (isset($_ENV["DB_SQLITE"]) && !empty($_ENV["DB_SQLITE"])) {
            $this->dbObject = new \PDO("sqlite:" . $_ENV["DB_SQLITE"]);
        } else {
            $this->dbObject = new \PDO("mysql:host=" . $_ENV["DB_HOST"] . ";
            dbname=" . $_ENV["DB_NAME"] ."", $_ENV["DB_USER"], $_ENV["DB_PASS"]);
            $tz = (new \DateTime('now', new \DateTimeZone('UTC')))->format('P');
            $this->dbObject->exec("SET time_zone='$tz';");
        }

        $this->dbObject->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function beginTransaction()
    {
        $this->dbObject->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->dbObject->commit();
    }

    public function rollbackTransaction()
    {
        $this->dbObject->rollback();
    }
}

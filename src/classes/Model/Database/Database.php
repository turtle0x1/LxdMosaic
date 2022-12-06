<?php

namespace dhope0000\LXDClient\Model\Database;

class Database
{
    public \PDO $dbObject;

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

        if ($this->dbObject == false) {
            throw new \Exception("Couldn't connect to database", 1);
        }

        $this->dbObject->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function beginTransaction() :void
    {
        $this->dbObject->beginTransaction();
    }

    public function commitTransaction() :void
    {
        $this->dbObject->commit();
    }

    public function rollbackTransaction() :void
    {
        $this->dbObject->rollback();
    }
}

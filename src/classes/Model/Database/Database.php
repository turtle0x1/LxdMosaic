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
        }
        $this->dbObject->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}

<?php

class Db {
	private $dbHost = DB_HOST;
	private $dbName = DB_NAME;
	private $dbUser = DB_USER;
	private $dbPass = DB_PASS;
	private $connection;

	public function __construct() {
		$this->connection = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
	}

    /**
	 * Simple getter, that returns the PDO object
	 *
     * @return PDO
     */
	public function getConnection() {
		return $this->connection;
	}

}


?>

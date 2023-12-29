<?php

namespace App;

use PDO;

class DB
{
	private static $pdo;
	/*private $host = "127.0.0.1";
	private $database = "graphqldatabase";
	private $username = "root";
	private $password = "";*/

	//private $host = "mysqldb";
	//private $host = "db";
	private $host = "localhost";
	private $database_name = "etlghum";
	private $username = "root";
	private $password = "";

	public function init()
	{
		try {
			//var_dump("mysql:host=" . $this->host . ";dbname=" . $this->database_name);exit;
			self::$pdo = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
			self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$pdo->exec("set names utf8");
		} catch (\PDOException $exception) {
			echo "Database could not be connected1: " . $exception->getMessage();
		}
		//return self::$pdo;
	}

	
	public $conn;

	public function Connection()
	{
		$this->conn = null;
		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
			$this->conn->exec("set names utf8");
		} catch (\PDOException $exception) {
			echo "Database could not be connected2: " . $exception->getMessage();
		}
		return $this->conn;
	}

	public static function selectOne($query)
	{
		$records = self::select($query);
		return array_shift($records);
	}

	public static function select($query)
	{
		$statement = self::$pdo->query($query);
		return $statement->fetchAll();
	}

	public static function affectingStatement($query)
	{
		$statement = self::$pdo->query($query);
		return $statement->rowCount();
	}

	public static function insert($query)
	{
		$statement = self::$pdo->prepare($query);
		$success = $statement->execute();
		return $success ? self::$pdo->lastInsertId() : null;
	}

	public static function update($sql)
	{
		$statement = self::$pdo->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}

	public static function delete($sql)
	{
		$statement = self::$pdo->prepare($sql);
		$statement->execute();
		return $statement->rowCount();
	}
}

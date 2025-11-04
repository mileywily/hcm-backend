<?php
namespace App\Lib;

use PDO;
use PDOException;

class ConnectionOld{
	
	private $driver="mysql";
	
	private $host ="xxxxxxxxxx";
    private $user="yyyyyyyy";
    private $pass='sid458321';
	



	private $dbName="hcm_db";
	private $charset="utf8";
  
  
	protected function conexion(){
		try{      
			$pdo = new PDO("{$this->driver}:host={$this->host};dbname={$this->dbName};charset={$this->charset}", $this->user, $this->pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			return $pdo;
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}
	}
}


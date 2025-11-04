<?php

namespace App\Lib;

use App\Lib\Response;
use App\Lib\ListaCodigoMensaje;

use PDO;
use PDOException;


abstract class Crud extends Connection{
    
	public $table;
    public $pdo;
	public $idTableName;
    public $response;
    public $responsefull;


    public function __construct($table, $idTableName) {
        
		$this->table = $table;
		$this->idTableName = $idTableName;
       
        $this->response = new Response();
        $this->responsefull = new Responsefull();

    }
	
	 public function getAll(){
        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("SELECT * FROM $this->table");
            $stm->execute();
            
            $this->pdo = null;


            return $stm->fetchAll(PDO::FETCH_OBJ);
        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }
	
	public function getById($id){
        try
        {
            $this->pdo = parent::conexion();

            $stm = $this->pdo->prepare("SELECT * FROM $this->table WHERE $this->idTableName = $id");
            $stm->execute(array($id));
            $this->pdo = null;

            return $stm->fetch(PDO::FETCH_OBJ);
        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_POR_ID, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }
	
	abstract function create();
    abstract function update();
	
	public function delete($id){
        try
        {
            $this->pdo = parent::conexion();

            $stm = $this->pdo->prepare("DELETE FROM $this->table WHERE $this->idTableName = ?");
            $stm->execute(array($id));

            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_REG, "Se ha eliminado correctamente el registro", ListaCodigoMensaje::$COD_ELIMINAR_REG );
            return $this->response;
        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }

    public function deleteAll(){
        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("DELETE FROM $this->table");
            $stm->execute();

            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, "Se ha eliminado correctamente el registro", ListaCodigoMensaje::$COD_ELIMINAR_TODOS_REG );
            return $this->response;
        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }
}

?>
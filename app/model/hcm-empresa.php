<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class EmpresaModel extends Crud
{

  /* campos de la tabla */
  public  $empresa_id;
  private $nombre;
  private $codigo;

  const TABLE 		= 'empresa'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'empresa_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->empresa_id	= $data["empresa_id"];
		$this->nombre 	    = $data["nombre"];
		$this->activo 	    = $data["codigo"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (nombre, codigo) VALUES (?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->codigo));
			$this->pdo = null;

	     	$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se ha creado correctamente el registro", ListaCodigoMensaje::$COD_AGREGAR_REG );
         	return $this->response;
		 
	   }catch(PDOException $e){
		
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
			return $this->response;
	   }
	}

	public function update(){
		try{
			$this->pdo = parent::conexion();
			$sql = "UPDATE $this->table SET nombre =?, codigo = ? WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->activo, $this->empresa_id));
			
			$this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
			return $this->response;
		 
	   }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
	   }
	}

	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['empresa_id']);
			}
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, "Se han eliminado los registros satisfactoriamente", ListaCodigoMensaje::$COD_ELIMINAR_TODOS_REG );
			return $this->response;
		
		}   
		catch(PDOException $e)
		{
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
		}
	}
	
}
<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class TipoCargoModel extends Crud
{

  /* campos de la tabla */
  public  $tipo_cargo_id;
  private $nombre;
  private $descripcion;

  const TABLE 		= 'tipo_cargo'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'tipo_cargo_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->tipo_cargo_id	= $data["tipo_cargo_id"];
		$this->nombre 	= $data["nombre"];
		$this->descripcion 	= $data["descripcion"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (nombre, descripcion) VALUES (?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->descripcion));
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
			$sql = "UPDATE $this->table SET nombre =?, descripcion = ? WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->descripcion, $this->tipo_cargo_id));
			
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
				$this->delete($data[$i]['tipo_cargo_id']);
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
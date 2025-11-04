<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class GrupoModel extends Crud
{

  /* campos de la tabla */
  public  $grupo_id;
  public $tipo_grupo_id; 
  private $nombre;
  private $descripcion;

  const TABLE 		= 'grupo'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'grupo_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->grupo_id			= $data["grupo_id"];
		$this->tipo_grupo_id	= $data["tipo_grupo_id"];
		$this->nombre 			= $data["nombre"];
		$this->descripcion 		= $data["descripcion"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (tipo_grupo_id, nombre, descripcion) VALUES (?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->tipo_grupo_id, $this->nombre,$this->descripcion));
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
			$sql = "UPDATE $this->table SET 
						tipo_grupo_id =?, 
						nombre =?, 
						descripcion = ? 
					WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
								$this->tipo_grupo_id, 
								$this->nombre, 
								$this->descripcion, 
								$this->grupo_id));
			
			$this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
			return $this->response;
			
		}catch(PDOException $e){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
				return $this->response;
		}
	}

	public function getAllCombo(){
        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("SELECT g.*, tp.nombre as nombre_tipo_grupo, g.nombre as label, grupo_id as value  
										FROM $this->table g
										INNER JOIN tipo_grupo tp ON g.tipo_grupo_id = tp.tipo_grupo_id

										");
            $stm->execute();
            
            $this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

			$this->response->result = $stm->fetchAll();
	  
			return $this->response->result;

        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }

	public function getGruposByTipo(){


        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("SELECT g.*, tp.nombre as nombre_tipo_grupo, g.nombre as label, grupo_id as value  
										FROM $this->table g
										INNER JOIN tipo_grupo tp ON g.tipo_grupo_id = tp.tipo_grupo_id
                                        WHERE g.tipo_grupo_id=?
										");

			$stm->execute(array(
			  $this->tipo_grupo_id
			  ));
            
            $this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

			$this->response->result = $stm->fetchAll();
	  
			return $this->response->result;

        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }

	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['grupo_id']);
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
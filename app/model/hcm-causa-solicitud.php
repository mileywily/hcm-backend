<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class CausaSolicitudModel extends Crud
{

  /* campos de la tabla */
  public  $causa_solicitud_id;
  private $estado_solicitud_id;
  private $motivo;
  private $descripcion;

  const TABLE 		= 'causa_solicitud'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'causa_solicitud_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->causa_solicitud_id	= $data["causa_solicitud_id"];
		$this->estado_solicitud_id 	= $data["estado_solicitud_id"];
		$this->motivo 	            = $data["motivo"];
        $this->descripcion 	        = $data["descripcion"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();

			$sql = "INSERT INTO $this->table (
                                                estado_solicitud_id, 
                                                motivo,
                                                descripcion
                                              ) 
                                              VALUES (?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				$this->estado_atencion_id,
				 $this->motivo, 
				 $this->descripcion
			));
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
							estado_solicitud_id =?, 
							motivo = ?, 
							descripcion = ? 
			WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
							$this->estado_solicitud_id, 
							$this->motivo, 
							$this->descripcion, 
							$this->causa_id
				));
			
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
            $stm = $this->pdo->prepare("SELECT *, es.nombre as nombre_estado , c.motivo as label, c.causa_solicitud_id as value  
										FROM $this->table c
										INNER JOIN estado_solicitud es on 
											es.estado_solicitud_id = c.estado_solicitud_id
										
										
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

	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['causa_solicitud_id']);
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
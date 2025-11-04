<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class CausaModel extends Crud
{

  /* campos de la tabla */
  public  $causa_id;
  private $estado_atencion_id;
  private $motivo;
  private $descripcion;

  const TABLE 		= 'causa'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'causa_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->causa_id	            = $data["causa_id"];
		$this->estado_atencion_id 	= $data["estado_atencion_id"];
		$this->motivo 	            = $data["motivo"];
        $this->descripcion 	        = $data["descripcion"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();

			$sql = "INSERT INTO $this->table (
                                                estado_atencion_id, 
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
							estado_atencion_id =?, 
							motivo = ?, 
							descripcion = ? 
			WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
							$this->estado_atencion_id, 
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
            $stm = $this->pdo->prepare("SELECT *, ea.nombre as nombre_estado , c.motivo as label, c.causa_id as value  
										FROM $this->table c
										INNER JOIN estado_atencion ea on 
											ea.estado_atencion_id = c.estado_atencion_id
										
										
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
				$this->delete($data[$i]['causa_id']);
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
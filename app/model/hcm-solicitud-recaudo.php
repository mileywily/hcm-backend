<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class SolicitudRecaudoModel extends Crud
{

  /* campos de la tabla */
  public  $solicitud_recaudo_id;
  public $solicitud_id;
  private $recaudo_id;

  const TABLE 		= 'solicitud_recaudo'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'solicitud_recaudo_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->solicitud_recaudo_id	= $data["solicitud_recaudo_id"];
		$this->solicitud_id 	= $data["solicitud_id"];
		$this->recaudo_id 		= $data["recaudo_id"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();

			//verifico que no se guarden relaciones repetidas
			$sql = "
			SELECT * FROM solicitud_recaudo
			WHERE solicitud_id = ? 	AND
			      recaudo_id = ?   
			";
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->solicitud_id, 
									$this->recaudo_id));

			$registros =  $stm->fetchAll();

		//	if (is_countable($registros) &&  count($registros) >= 1){
			if (count($registros) >= 1){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "El recaudo ya se encuentra asociado a la solicitud. ", ListaCodigoMensaje::$COD_ERROR );
			}else{
				$sql = "INSERT INTO $this->table (solicitud_id, recaudo_id) VALUES (?,?)";
				$stm = $this->pdo->prepare($sql);
				$stm->execute(array($this->solicitud_id, 
									$this->recaudo_id));

				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Recaudo agregado", ListaCodigoMensaje::$COD_AGREGAR_REG );
			}

			$this->pdo = null;

         	return $this->response;
		 
	   }catch(PDOException $e){
		
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
			return $this->response;
	   }
	}

	public function update(){
		try{
		 
			$this->pdo = parent::conexion();
			$sql = "UPDATE $this->table 
						SET solicitud_id =?,
							recaudo_id =?,

					WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				$this->solicitud_id, 
				$this->recaudo_id, 
				$this->solicitud_recaudo_id));
			
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
            $stm = $this->pdo->prepare("SELECT sr.*, r.nombre as label, sr.solicitud_recaudo_id as value  
										FROM $this->table sr
										INNER JOIN solicitud s ON sr.solicitud_id = s.solicitud_id
										INNER JOIN recaudo r ON sr.recaudo_id = r.recaudo_id
										
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

    //Filtros: solicitud_id
	//Buscar recaudos entregados por solicitud
	public function getRecaudosBySolicitud(){
		try
		{
			$this->pdo = parent::conexion();
			
			$sql = "
					SELECT sr.*, 
					r.recaudo_id as value, 
					r.nombre as label,
					r.nombre name,
					r.recaudo_id code
					FROM $this->table sr
					INNER JOIN solicitud sol on sol.solicitud_id = sr.solicitud_id
				    INNER JOIN recaudo r on sr.recaudo_id = r.recaudo_id
				WHERE sr.solicitud_id= ? ORDER BY sr.recaudo_id
			";  
		
			$stm = $this->pdo->prepare($sql);
	
			
			$stm->execute(array(
			$this->solicitud_id
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
				$this->delete($data[$i]['solicitud_recaudo_id']);
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
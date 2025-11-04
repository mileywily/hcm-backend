<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class SolicitudDiagnosticoModel extends Crud
{

  /* campos de la tabla */
  public  $solicitud_diagnostico_id;
  public $solicitud_id;
  private $diagnostico_id;

  const TABLE 		= 'solicitud_diagnostico'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'solicitud_diagnostico_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->solicitud_diagnostico_id		= $data["solicitud_diagnostico_id"];
		$this->solicitud_id 			= $data["solicitud_id"];
		$this->diagnostico_id 				= $data["diagnostico_id"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (solicitud_id, diagnostico_id) VALUES (?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->solicitud_id, 
								$this->diagnostico_id));
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
			$sql = "UPDATE $this->table 
						SET solicitud_id =?,
							diagnostico_id =?,

					WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				$this->solicitud_id, 
				$this->diagnostico_id, 
				$this->solicitud_diagnostico_id));
			
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
            $stm = $this->pdo->prepare("SELECT se.*, e.nombre as label, se.solicitud_diagnostico_id as value  
										FROM $this->table se
										INNER JOIN solicitud s ON se.solicitud_id = s.solicitud_id
										INNER JOIN diagnostico e ON se.diagnostico_id = e.diagnostico_id
										
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
	//Busca en la relación de de la tabla grupo_examen para 
	//Solicitudes de tipo LABORATORIO (Diagnosticos solicitados por soliictud)
	public function getDiagnosticosBySolicitud(){
		try 
		{
			$this->pdo = parent::conexion();
			
			$sql = "
					SELECT se.*, 
					ge.grupo_id as grupo_id, 
					g.nombre as nombre_grupo,
					e.nombre as nombre_examen,
					e.examen_id as value, 
					e.nombre as label,
					e.nombre name,
					e.examen_id code,
					CASE WHEN e.examen_id in 
						(SELECT ae.examen_id 
							FROM atencion_examen ae 
							INNER JOIN atencion a ON a.atencion_id = ae.atencion_id
							AND a.solicitud_id = $this->solicitud_id and ae.is_confirmado=1 AND a.estado_atencion_id != 4) 
						THEN 1 ELSE 0 END AS asignado
					FROM $this->table se
					INNER JOIN solicitud sol on sol.solicitud_id = se.solicitud_id
					INNER JOIN examen e on e.examen_id  = se.examen_id
				    INNER JOIN grupo_examen ge on se.examen_id = ge.examen_id
				    INNER JOIN grupo g on ge.grupo_id = g.grupo_id
				WHERE se.solicitud_id= $this->solicitud_id
			";  
		
			$stm = $this->pdo->prepare($sql);
	
			
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
	//Examenes solicitados y asignados por solicitud
	public function getDiagnosticosAsignadosBySolicitud(){
		try 
		{
			
			$this->pdo = parent::conexion();
			
			$sql = "SELECT se.*, 
					ge.tipo_diagnostico_id as grupo_id, 
					ge.nombre as grupo,
					e.nombre as nombre_examen,
					e.diagnostico_id as value, 
					e.nombre as label,
					e.nombre name,
					e.diagnostico_id code,
					1 as asignado
					FROM $this->table se
					INNER JOIN solicitud sol on sol.solicitud_id = se.solicitud_id
					INNER JOIN diagnostico e on e.diagnostico_id  = se.diagnostico_id
				    INNER JOIN tipo_diagnostico ge on e.tipo_diagnostico_id = ge.tipo_diagnostico_id	
                    WHERE se.solicitud_id= $this->solicitud_id					
			";  
		
			$stm = $this->pdo->prepare($sql);
	
			
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
				$this->delete($data[$i]['solicitud_diagnostico_id']);
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
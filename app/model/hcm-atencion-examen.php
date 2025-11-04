<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class AtencionExamenModel extends Crud
{

  /* campos de la tabla */
  public  $atencion_examen_id;
  public  $atencion_id;
  private $examen_id;
  private $is_confirmado;

  const TABLE 		= 'atencion_examen'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'atencion_examen_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->atencion_examen_id		= $data["atencion_examen_id"];
		$this->atencion_id 				= $data["atencion_id"];
		$this->examen_id 				= $data["examen_id"];
		$this->is_confirmado 		    = $data["is_confirmado"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (atencion_id, examen_id, is_confirmado) VALUES (?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->atencion_id, 
								$this->examen_id));
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
						SET atencion_id =?,
							examen_id =?,
							is_confirmado =?

					WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				$this->atencion_id, 
				$this->examen_id, 
				$this->is_confirmado, 
				$this->atencion_examen_id));
			
			$this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
			return $this->response;
			
		}catch(PDOException $e){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
				return $this->response;
		}
	}

	public function updateLote($datos){

		try{
		 
			$this->pdo = parent::conexion();

			if ($datos){
				//Inhabilitar los no seleccionados
				$sql = "UPDATE $this->table 
				SET  is_confirmado = 0
				WHERE atencion_id = ? ";

				$stm = $this->pdo->prepare($sql);
				$stm->execute(array($datos['atencion_id'] ));

				//Habilitar los seleccionados
                foreach($datos['examenes_confirmados'] as $examen) { 
					$sql = "UPDATE $this->table 
							SET  is_confirmado = ?
							WHERE $this->idTableName =  ? ";
					
					$stm = $this->pdo->prepare($sql);
					$stm->execute(array(
						1, 
						$examen['atencion_examen_id'] ));
                }
            }

			$this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el(los) registro(s)", ListaCodigoMensaje::$COD_MODIFICAR_REG );
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
            $stm = $this->pdo->prepare("SELECT ae.*, e.nombre as label, ae.atencion_examen_id as value  
										FROM $this->table ae
										INNER JOIN atencion a ON ae.atencion_id = a.atencion_id
										INNER JOIN examen e ON ae.examen_id = e.examen_id
										
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

	
	//Filtros: atencion_id
	//Busca en la relación de de la tabla grupo_examen para 
	//Atenciones de tipo LABORATORIO
	public function getExamenesByAtencion(){
		try 
		{
			$this->pdo = parent::conexion();
			
			$sql = "
					SELECT ae.*, 
					ge.grupo_id as grupo_id, 
					g.nombre as nombre_grupo,
					e.nombre as nombre_examen,
					e.examen_id as value, 
					e.nombre as label,
					e.nombre name,
					e.examen_id code
					FROM $this->table ae
					INNER JOIN atencion a on a.atencion_id = ae.atencion_id
					INNER JOIN examen e on e.examen_id  = ae.examen_id
				    INNER JOIN grupo_examen ge on ae.examen_id = ge.examen_id
				    INNER JOIN grupo g on ge.grupo_id = g.grupo_id
				WHERE ae.atencion_id= ?
			";  

			$stm = $this->pdo->prepare($sql);
	
			
			$stm->execute(array(
			$this->atencion_id
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
				$this->delete($data[$i]['atencion_examen_id']);
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
<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class MedicoEspecialidadModel extends Crud
{

  /* campos de la tabla */
  public  $medico_especialidad_id;
  public $medico_id;
  public $especialidad_id;

  const TABLE 		= 'medico_especialidad'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'medico_especialidad_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->medico_especialidad_id	= $data["medico_especialidad_id"];
		$this->medico_id 	        	= $data["medico_id"];
        $this->especialidad_id 	        = $data["especialidad_id"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();

			//verifico que no se guarden relaciones repetidas
			$sql = "
					SELECT * FROM medico_especialidad 
					WHERE medico_id = $this->medico_id   AND
					especialidad_id = $this->especialidad_id 	
					";
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute();

			$registros =  $stm->fetchAll();

			if (is_countable($registros) &&  count($registros) >= 1){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "El médico ya se encuentra asociado a la especialidad. ", ListaCodigoMensaje::$COD_ERROR );
			}else{
				$sql = "INSERT INTO $this->table (
					medico_id,
					especialidad_id
				  ) 
				  VALUES (?,?)";
				$stm = $this->pdo->prepare($sql);
				$stm->execute(array(
									$this->medico_id, 
									$this->especialidad_id
				));
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se ha creado correctamente el registro", ListaCodigoMensaje::$COD_AGREGAR_REG );

			};
			
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
		 
            $sql = "UPDATE $this->table SET 
                            medico_id =?, 
                            especialidad_id = ?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->medico_id, 
                            $this->especialidad_id,  
                            $this->medico_especialidad_id
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
            $stm = $this->pdo->prepare("SELECT *, 
												concat(m.nombres, ' ' , m.apellidos) as medico,
												e.nombre as especialidad,
												e.nombre as label,
												me.medico_especialidad_id as value
										FROM $this->table me
										INNER JOIN medico m 
											on m.medico_id = me.medico_id 										INNER JOIN especialidad e 
											on e.especialidad_id  = me.especialidad_id 
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
	

	public function getEspecialidaByMedico(){
        try
        {
            $this->pdo = parent::conexion();
			
            $stm = $this->pdo->prepare("SELECT *, 
											   concat(m.nombres, ' ' , m.apellidos) as medico,
											   e.especialidad_id value,
											   e.nombre label,
											   e.nombre as especialidad
										FROM $this->table me
										INNER JOIN medico m 
											on m.medico_id = me.medico_id
										INNER JOIN especialidad e 
											on e.especialidad_id  = me.especialidad_id
										WHERE ( m.medico_id = ? OR '".$this->medico_id."' IS NULL OR '".$this->medico_id. "'=' ' OR '".$this->medico_id. "'='null')	");

			$stm->execute(array($this->medico_id));
            
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


	public function getMedicoByEspecialidad(){

		try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare(" SELECT 
					
					m.medico_especialidad_id,
					m.especialidad_id,
					e.nombre especialidad,
					mc.*,
					concat(mc.nombres,' ', mc.apellidos) label,
					mc.medico_id value

				FROM $this->table m
				INNER JOIN especialidad e ON e.especialidad_id = m.especialidad_id
				INNER JOIN medico mc ON mc.medico_id = m.medico_id and mc.activo = 1
				WHERE (m.especialidad_id = ? OR '".$this->especialidad_id."' IS NULL OR '".$this->especialidad_id. "'=' ' OR '".$this->especialidad_id. "'='null')
                order by e.nombre, concat(mc.nombres,' ', mc.apellidos) 				");
            $stm->execute(array($this->especialidad_id));
            			
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
				$this->delete($data[$i]['medico_especialidad_id']);
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
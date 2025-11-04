<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class ServicioEspecialidadModel extends Crud
{

  /* campos de la tabla */
  public 	$servicio_especialidad_id;
  public 	$especialidad_id;
  public 	$tipo_atencion_id;
  private 	$servicio_id;
  private   $is_estandar;
  private   $is_procedimiento;

  const TABLE 		= 'servicio_especialidad'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'servicio_especialidad_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->servicio_especialidad_id	= $data["servicio_especialidad_id"];
		$this->especialidad_id 	        = $data["especialidad_id"];
		$this->tipo_atencion_id 	    = $data["tipo_atencion_id"];
        $this->servicio_id 	            = $data["servicio_id"];
		$this->is_estandar 	            = $data["is_estandar"];
		$this->is_procedimiento 	    = $data["is_procedimiento"];

	}
  
	public function create(){
		try{

            $this->pdo = parent::conexion();

			//verifico que no se guarden relaciones repetidas
			$sql = "
			SELECT * FROM servicio_especialidad 
			WHERE servicio_id = $this->servicio_id   AND
			especialidad_id = $this->especialidad_id 	
			";
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute();

			$registros =  $stm->fetchAll();

			if (is_countable($registros) &&  count($registros) >= 1){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "El servicio ya se encuentra asociado a la especialidad. ", ListaCodigoMensaje::$COD_ERROR );
			}else{
				$sql = "INSERT INTO $this->table (
                                                especialidad_id,
                                                servicio_id,
												is_estandar,
												is_procedimiento
                                              ) 
                                              VALUES (?,?,?,?)";
				$stm = $this->pdo->prepare($sql);
				$stm->execute(array(
				 $this->especialidad_id, 
				 $this->servicio_id,
				 $this->is_estandar,
				 $this->is_procedimiento
				));

				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se ha creado correctamente el registro", ListaCodigoMensaje::$COD_AGREGAR_REG );

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
            $sql = "UPDATE $this->table SET 
                            especialidad_id =?, 
                            servicio_id = ?,
							is_estandar = ?,
							is_procedimiento = ?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->especialidad_id, 
                            $this->servicio_id,  
							$this->is_estandar,  
							$this->is_procedimiento,
                            $this->servicio_especialidad_id
                ));
            
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
            return $this->response;
            
        }catch(PDOException $e){
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                return $this->response;
        }
	}


	//Filtros: especialidad_id
	//Obtener todos los servicios por especialidad
	public function getAllByEspecialidad(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
				SELECT se.*, 
					ta.tipo_atencion_id as tipo_atencion_id, 
					ta.nombre as nombre_tipo_atencion,
					s.servicio_id value,
					s.nombre label,
					s.nombre servicio,
					e.nombre especialidad,
					s.nombre label,
					s.nombre name,
					s.servicio_id code
					
	
				FROM $this->table se 
				INNER JOIN especialidad e ON e.especialidad_id = se.especialidad_id
				INNER JOIN servicio s ON s.servicio_id = se.servicio_id
				INNER JOIN servicio_tipoatencion st on se.servicio_id = st.servicio_id
				INNER JOIN tipo_atencion ta on st.tipo_atencion_id = ta.tipo_atencion_id
			
			WHERE 
			  (se.especialidad_id = ?  OR '".$this->especialidad_id."' IS NULL OR '".$this->especialidad_id. "'=' ' OR '".$this->especialidad_id. "'='null')
			  
			ORDER BY e.nombre,s.nombre 
		  ";  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->especialidad_id
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

	

	//Filtros: especialidad_id
	//Obtener todos los servicios por especialidad
	public function getAllByEspecialidadTipo(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
				SELECT se.*, 
					ta.tipo_atencion_id as tipo_atencion_id, 
					ta.nombre as nombre_tipo_atencion,
					s.servicio_id value,
					s.nombre label,
					s.nombre servicio,
					e.nombre especialidad,
					s.nombre label,
					s.nombre name,
					s.servicio_id code
					
	
				FROM $this->table se 
				INNER JOIN especialidad e ON e.especialidad_id = se.especialidad_id
				INNER JOIN servicio s ON s.servicio_id = se.servicio_id
				INNER JOIN servicio_tipoatencion st on se.servicio_id = st.servicio_id
				INNER JOIN tipo_atencion ta on st.tipo_atencion_id = ta.tipo_atencion_id
			
			WHERE 
			  se.especialidad_id = ? and st.tipo_atencion_id = ?
			  
			ORDER BY s.nombre 
		  ";  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->especialidad_id,
			$this->tipo_atencion_id
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


	//Filtros: especialidad_id
	//Obtener todos los servicios a los que se crean ordenes automáticas  por especialidad
	//Los servicios estandard son todos aquellos servicios que van incluidos dentro de una consulta
	//Pero se le generarn números de ordenes diferentes

	public function getEstandardByEspecialidad(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
		  	SELECT se.*, 
			    ta.tipo_atencion_id as tipo_atencion_id, 
			    ta.nombre as nombre_tipo_atencion,
				s.servicio_id value,
				s.nombre label,
				s.nombre name,
				s.servicio_id code
 
 			FROM $this->table se 
 			INNER JOIN servicio s ON s.servicio_id = se.servicio_id
			INNER JOIN servicio_tipoatencion st on se.servicio_id = st.servicio_id
			INNER JOIN tipo_atencion ta on st.tipo_atencion_id = ta.tipo_atencion_id
			
			WHERE 
			  se.especialidad_id = ? and se.is_estandar = 1 and se.servicio_id != 1 
		  ";  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->especialidad_id
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


	public function getAllCombo(){
        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("SELECT *, 
												e.nombre as nombre_especialidad, 
												s.nombre as nombre_servicio
										FROM $this->table se
										INNER JOIN especialidad e 
											on e.especialidad_id = se.especialidad_id
										INNER JOIN servicio s 
											on s.servicio_id  = se.servicio_id
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
				$this->delete($data[$i]['servicio_especialidad_id']);
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
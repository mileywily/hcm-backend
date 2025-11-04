<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class GrupoExamenModel extends Crud
{

  /* campos de la tabla */
  public 	$grupo_examen_id;
  public 	$grupo_id;
  private 	$examen_id;

  const TABLE 		= 'grupo_examen'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'grupo_examen_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->grupo_examen_id			= $data["grupo_examen_id"];
		$this->grupo_id 	        	= $data["grupo_id"];
        $this->examen_id 	            = $data["examen_id"];

	}
  
	public function create(){
		try{

            $this->pdo = parent::conexion();

			//verifico que no se guarden relaciones repetidas
			$sql = "
						SELECT * FROM grupo_examen 
						WHERE 
								examen_id 		= $this->examen_id   AND
								grupo_id 		= $this->grupo_id 	
					";
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute();

			$registros =  $stm->fetchAll();

			if (is_countable($registros) && count($registros) >= 1){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "El servicio ya se encuentra asociado al grupo. ", ListaCodigoMensaje::$COD_ERROR );
			}else{
				$sql = "INSERT INTO $this->table (
                                                grupo_id,
                                                examen_id
                                              ) 
                                              VALUES (?,?)";
				$stm = $this->pdo->prepare($sql);
				$stm->execute(array(
				 $this->grupo_id, 
				 $this->examen_id
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
                            grupo_id =?, 
                            examen_id = ?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->grupo_id, 
                            $this->examen_id,  
                            $this->grupo_examen_id
                ));
            
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
            return $this->response;
            
        }catch(PDOException $e){
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                return $this->response;
        }
	}


	//Filtros: grupo_id
	//Obtener todos los examenes por grupo
	public function getAllByGrupo(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
					SELECT ge.*,
						e.examen_id value,
						e.nombre label,
						e.nombre servicio,
						g.nombre grupo,
						e.nombre name,
						e.examen_id code
					FROM $this->table ge
					INNER JOIN grupo g ON g.grupo_id = ge.grupo_id
					INNER JOIN examen e ON e.examen_id = ge.examen_id
					
					WHERE 
						(ge.grupo_id = ? OR '".$this->grupo_id."' IS NULL OR '".$this->grupo_id. "'=' ' OR '".$this->grupo_id. "'='null')
						
					ORDER BY g.nombre,e.nombre 
		  ";  
		  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->grupo_id
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
												g.nombre as nombre_grupo, 
												e.nombre as nombre_servicio
										FROM $this->table ge
										INNER JOIN grupo g 
											on g.grupo_id = ge.grupo_id
										INNER JOIN examen e 
											on e.examen_id  = ge.examen_id
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
				$this->delete($data[$i]['grupo_examen_id']);
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
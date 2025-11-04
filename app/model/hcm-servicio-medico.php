<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class ServicioMedicoModel extends Crud
{

  /* campos de la tabla */
  public  $servicio_medico_id;
  private $servicio_id;
  private $precio;

  const TABLE 		= 'servicio_medico'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'servicio_medico_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->servicio_medico_id		= $data["servicio_medico_id"];
		$this->medico_id 	        	= $data["medico_id"];
        $this->servicio_id 	            = $data["servicio_id"];
		$this->precio                   = $data["precio"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (
                                                medico_id,
                                                servicio_id,
												precio
                                              ) 
                                              VALUES (?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				 $this->medico_id, 
				 $this->servicio_id,
				 $this->precio
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
                            medico_id =?, 
                            servicio_id = ?,
							precio =? 
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->medico_id, 
                            $this->servicio_id,
                            $this->precio,							
                            $this->servicio_medico_id
                ));
            
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
            return $this->response;
            
        }catch(PDOException $e){
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                return $this->response;
        }
	}


	//Filtros: medico_id
	public function getAllByMedico(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
		  	SELECT sm.*,
			    ta.tipo_atencion_id as tipo_atencion_id, 
			    ta.nombre as nombre_tipo_atencion,
				s.servicio_id value,
				s.nombre label , 
				 CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre
 
 			FROM $this->table sm 
 			INNER JOIN servicio s on s.servicio_id = sm.servicio_id
		    INNER JOIN medico m on m.medico_id = sm.medico_id 
			INNER JOIN servicio_tipoatencion st on sm.servicio_id = st.servicio_id
			INNER JOIN tipo_atencion ta on st.tipo_atencion_id = ta.tipo_atencion_id
			
			WHERE 
			 ( sm.medico_id = ?  OR '".$this->medico_id."' IS NULL OR '".$this->medico_id. "'=' ' OR '".$this->medico_id. "'='null' )
		  ";  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->medico_id
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
												concat(m.nombres, ' ' , m.apellidos) as nombre
										FROM $this->table sm
										INNER JOIN medico m 
											on m.medico_id = sm.medico_id
										INNER JOIN servicio s 
											on s.servicio_id  = sm.servicio_id
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
				$this->delete($data[$i]['servicio_medico_id']);
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
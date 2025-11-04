<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class ServicioTipoModel extends Crud
{

  /* campos de la tabla */
  public  $servicio_tipoatencion_id;
  public  $tipo_atencion_id;
  private $servicio_id;

  const TABLE 		= 'servicio_tipoatencion'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'servicio_tipoatencion_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->servicio_tipoatencion_id	= $data["servicio_tipoatencion_id"];
		$this->tipo_atencion_id 	    = $data["tipo_atencion_id"];
        $this->servicio_id 	            = $data["servicio_id"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();


			//verifico que no se guarden relaciones repetidas
			$sql = "
			SELECT * FROM servicio_tipoatencion
			WHERE servicio_id = $this->servicio_id   AND
			tipo_atencion_id = $this->tipo_atencion_id 	
			";
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute();

			$registros =  $stm->fetchAll();

			if (is_countable($registros) && count($registros) >= 1){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "El servicio ya se encuentra asociado al tipo de atención. ", ListaCodigoMensaje::$COD_ERROR );
			}else{
				$sql = "INSERT INTO $this->table (
					tipo_atencion_id,
					servicio_id
				  ) 
				  VALUES (?,?)";

				$stm = $this->pdo->prepare($sql);
				$stm->execute(array(
					$this->tipo_atencion_id, 
					$this->servicio_id
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
                            tipo_atencion_id =?, 
                            servicio_id = ?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->tipo_atencion_id, 
                            $this->servicio_id,  
                            $this->servicio_tipoatencion_id
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
            $stm = $this->pdo->prepare("		
										SELECT *, 
												t.nombre as nombre_tipo_atencion, 
												s.nombre as nombre_servicio,
												s.servicio_id as value, 
												s.nombre as label
										FROM $this->table st
										INNER JOIN tipo_atencion t 
											on t.tipo_atencion_id = st.tipo_atencion_id
										INNER JOIN servicio s 
											on s.servicio_id  = st.servicio_id
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
	public function getAllByAtencion(){
	try
	{
		$this->pdo = parent::conexion();
		
		$sql = "
			SELECT *, t.nombre as nombre_tipo_atencion, s.nombre as nombre_servicio,
					 s.servicio_id as value, s.nombre as label
			FROM $this->table st
			INNER JOIN tipo_atencion t 
				on t.tipo_atencion_id = st.tipo_atencion_id
			INNER JOIN servicio s 
				on s.servicio_id  = st.servicio_id
			WHERE (st.tipo_atencion_id= ?  OR '".$this->tipo_atencion_id."' IS NULL OR '".$this->tipo_atencion_id. "'=' ' OR '".$this->tipo_atencion_id. "'='null')
			order by t.nombre,s.nombre
		";  
	
		$stm = $this->pdo->prepare($sql);

		
		$stm->execute(array(
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

    	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['servicio_tipoatencion_id']);
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
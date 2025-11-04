<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class SolicitudPresupuestoModel extends Crud
{

  /* campos de la tabla */
  public  $solicitud_presupuesto_id;
  public  $solicitud_id;
  private $servicio_id;
  private $proveedor_id;
  private $precio_dolar;
  private $precio_bs;
  private $precio_tasa;
  private $activo;

  const TABLE 		= 'solicitud_presupuesto'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'solicitud_presupuesto_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->solicitud_presupuesto_id	= $data["solicitud_presupuesto_id"];
		$this->solicitud_id 	    	= $data["solicitud_id"];
        $this->servicio_id 	            = $data["servicio_id"];
	    $this->proveedor_id 	    	= $data["proveedor_id"];
        $this->precio_dolar 	        = $data["precio"];
	    $this->precio_bs 	    	    = $data["precio_bs"];
        $this->precio_tasa 	                = $data["precio_tasa"];
		$this->activo 	                = $data["activo"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();


			//verifico que no se guarden relaciones repetidas
			$sql = "
			SELECT * FROM solicitud_presupuesto_id
			WHERE solicitud_id = $this->solicitud_id 	AND
			      servicio_id = $this->servicio_id   AND  proveedor_id = $this->proveedor_id
			";
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute();

			$registros =  $stm->fetchAll();

			if (is_countable($registros) &&  count($registros) >= 1){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "El presupuesto del servicio ya se encuentra asociado a la solicitud. ", ListaCodigoMensaje::$COD_ERROR );
			}else{
				$sql = "INSERT INTO $this->table (
					solicitud_id,
					servicio_id,
					proveedor_id,
					precio_dolar,
					precio_bs,
					precio_tasa,
					activo
				  ) 
				  VALUES (?,?,?,?,?,?,?)";

				$stm = $this->pdo->prepare($sql);
				$stm->execute(array(
					$this->solicitud_id, 
					$this->servicio_id,
					$this->proveedor_id,
					$this->precio_dolar,
					$this->precio_bs,
					$this->precio_tasa,
					$this->activo
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
							precio_dolar= ?,
							precio_bs= ?,
							precio_tasa= ?,
							activo= ?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->precio_dolar, 
                            $this->precio_bs, 
                            $this->precio_tasa, 
                            $this->activo,  
                            $this->solicitud_presupuesto_id
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
										SELECT ss.*, 
												s.nombre as nombre_servicio,
												p.nombre as nombre_proveedor,
												s.servicio_id as value, 
												s.nombre as label

										FROM $this->table ss
										INNER JOIN solicitud sol 
											on sol.solicitud_id = ss.solicitud_id
										INNER JOIN servicio s 
											on s.servicio_id  = ss.servicio_id
										INNER JOIN proveedor p 
											on p.proveedor_id  = ss.proveedor_id
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
	//Busca en la relación de de la tabla servicio_tipoatencion para 
	//Atenciones de tipo CONSULTA y de tipo ESTUDIOS ESPECIALES
	public function getAllBySolicitud(){
		try
		{
			$this->pdo = parent::conexion();
			
			$sql = "
					SELECT ss.*, 
					s.nombre as nombre_servicio,
					p.nombre as nombre_proveedor,
					s.servicio_id as value, 
					s.nombre as label,
					s.nombre name,
					s.servicio_id code
					FROM $this->table ss
					INNER JOIN solicitud sol on sol.solicitud_id = ss.solicitud_id
					INNER JOIN servicio s on s.servicio_id  = ss.servicio_id
					INNER JOIN proveedor p on p.proveedor_id  = ss.proveedor_id
				WHERE ss.solicitud_id= ?
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

	//Filtros: solicitud_id
	public function getAllAutomaticosBySolicitud(){
		try
		{
			$this->pdo = parent::conexion();
			
			$sql = "
					SELECT ss.*, 
					ta.tipo_atencion_id as tipo_atencion_id, 
					ta.nombre as nombre_tipo_atencion,
					s.nombre as nombre_servicio,
					s.servicio_id as value, 
					s.nombre as label,
					s.nombre name,
					s.servicio_id code
					FROM $this->table ss
					INNER JOIN solicitud sol on sol.solicitud_id = ss.solicitud_id
					INNER JOIN servicio s on s.servicio_id  = ss.servicio_id
					INNER JOIN servicio_tipoatencion st on ss.servicio_id = st.servicio_id
					INNER JOIN tipo_atencion ta on st.tipo_atencion_id = ta.tipo_atencion_id
				WHERE ss.solicitud_id= ? AND ss.servicio_id != 1
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
	
	//Filtros: solicitud_id
	//Busca en la relación de de la tabla grupo_servicio para 
	//Solicitudes de tipo IMAGEN
	public function getGrupoServicioBySolicitud(){
		try
		{
			$this->pdo = parent::conexion();
			
			$sql = "
					SELECT ss.*, 
					gs.grupo_id as grupo_id, 
					g.nombre as nombre_grupo,
					s.nombre as nombre_servicio,
									p.nombre as nombre_proveedor,
					s.servicio_id as value, 
					s.nombre as label,
					s.nombre name,
					s.servicio_id code,
					CASE WHEN ss.solicitud_id in 
					(SELECT a.solicitud_id 
						FROM atencion a
						WHERE a.solicitud_id = ss.solicitud_id  AND 
                         a.servicio_id = ss.servicio_id AND a.estado_atencion_id != 4) 
					THEN 1 ELSE 0 END AS asignado
					FROM $this->table ss
					INNER JOIN solicitud sol on sol.solicitud_id = ss.solicitud_id
					INNER JOIN servicio s on s.servicio_id  = ss.servicio_id
					INNER JOIN proveedor p on p.proveedor_id  = ss.proveedor_id
				    INNER JOIN grupo_servicio gs on ss.servicio_id = gs.servicio_id
				    INNER JOIN grupo g on gs.grupo_id = g.grupo_id
				WHERE ss.solicitud_id= ?
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

    //Elimina varios registros
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['solicitud_presupuesto_id']);
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
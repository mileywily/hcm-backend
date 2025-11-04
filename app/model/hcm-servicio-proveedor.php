<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class ServicioProveedorModel extends Crud
{

  /* campos de la tabla */
  public  $servicio_proveedor_id;
  private $servicio_id;
  private $precio;

  const TABLE 		= 'servicio_proveedor'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'servicio_proveedor_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->servicio_proveedor_id	= $data["servicio_proveedor_id"];
		$this->proveedor_id 	        = $data["proveedor_id"];
        $this->servicio_id 	            = $data["servicio_id"];
		$this->precio                   = $data["precio"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (
                                                proveedor_id,
                                                servicio_id,
												precio
                                              ) 
                                              VALUES (?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				 $this->proveedor_id, 
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
	
	
	//Filtros: proveedor_id
	public function getAllByProveedor(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
		  	SELECT sm.*,
			    ta.tipo_atencion_id as tipo_atencion_id, 
			    ta.nombre as nombre_tipo_atencion,
				s.servicio_id value,
				s.nombre label , 
				 p.nombre as proveedor_nombre
 
 			FROM $this->table sm 
 			INNER JOIN servicio s on s.servicio_id = sm.servicio_id
		    INNER JOIN proveedor p on p.proveedor_id = sm.proveedor_id 
			INNER JOIN servicio_tipoatencion st on sm.servicio_id = st.servicio_id
			INNER JOIN tipo_atencion ta on st.tipo_atencion_id = ta.tipo_atencion_id
			
			WHERE 
			 ( sm.proveedor_id = ?  OR '".$this->proveedor_id."' IS NULL OR '".$this->proveedor_id. "'=' ' OR '".$this->proveedor_id. "'='null' )
		  ";  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->proveedor_id
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
	
	

	public function update(){
		try{
            $this->pdo = parent::conexion();
            $sql = "UPDATE $this->table SET 
                            proveedor_id =?, 
                            servicio_id = ?,
							precio =?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->proveedor_id, 
                            $this->servicio_id, 
                            $this->precio,							
                            $this->servicio_proveedor_id
                ));
            
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
            return $this->response;
            
        }catch(PDOException $e){
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                return $this->response;
        }
	}

    	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['servicio_proveedor_id']);
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
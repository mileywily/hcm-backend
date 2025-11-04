<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class TipoCoberturaModel extends Crud
{

  /* campos de la tabla */
  public  $tipo_cobertura_id;
  private $nombre;
  private $is_activo;


  const TABLE 		= 'tipo_cobertura'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'tipo_cobertura_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->servicio_id	= $data["tipo_cobertura_id"];
		$this->nombre 	    = $data["nombre"];
		$this->is_activo 	= $data["is_activo"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (
                                                nombre
                                              ) 
                                              VALUES (?, ?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				 $this->nombre, 
				 $this->is_activo
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
                            nombre =?,
							is_activo =?
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->nombre,  
                            $this->is_activo
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
            $stm = $this->pdo->prepare("SELECT *, nombre as label, tipo_cobertura_id as value  
										FROM $this->table");
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

	//Solo tipos de coberturas activas
	public function getAllComboActivas(){
        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("SELECT *, nombre as label, tipo_cobertura_id as value  
										FROM $this->table
										WHERE is_activo = 1

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
				$this->delete($data[$i]['tipo_cobertura_id']);
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
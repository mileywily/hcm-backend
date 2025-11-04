<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class TasaModel extends Crud
{

  /* campos de la tabla */
  public  $tasa_id;
  private $nombre;
  private $mes;
  private $anio;
  private $moneda;

  const TABLE 		= 'tasas_cambio'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'tasa_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

			$this->tasa_id     = isset($data["tasa_id"])? $data["tasa_id"]: null;
			$this->nombre 	      = isset($data["nombre"])? $data["nombre"]: null;
			$this->mes 	  = isset($data["mes"])? $data["mes"]: null;
		    $this->anio 	  = isset($data["anio"])? $data["anio"]: null;
            $this->moneda 	  = isset($data["moneda"])? $data["moneda"]: null;
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();

			$sql = "INSERT INTO $this->table (
                                                nombre,
                                                mes,
												anio,
												moneda
                                              ) 
                                              VALUES (?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
				 $this->nombre, 
				 $this->mes,
				 $this->anio,
				 $this->moneda
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
							nombre = ?, 
							mes = ?,
							anio = ?,
							moneda = ?		
			WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
							$this->nombre, 
							$this->mes, 
							$this->anio,
							$this->moneda,
				            $this->tasa_id
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
            $stm = $this->pdo->prepare("SELECT *, nombre as label, tasa_id as value  
										FROM $this->table ");
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
				$this->delete($data[$i]['tasa_id']);
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
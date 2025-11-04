<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class GrupoDiagnosticoModel extends Crud
{

  /* campos de la tabla */
  public  $grupo_diagnostico_id;
  private $nombre;

  const TABLE 		= 'grupo_diagnostico'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'grupo_diagnostico_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->grupo_diagnostico_id	= $data["grupo_diagnostico_id"];
		$this->nombre 	= $data["nombre"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (nombre) VALUES (?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre));
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
			$sql = "UPDATE $this->table SET nombre =? WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->grupo_diagnostico_id));
			
			$this->pdo = null;

			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
			return $this->response;
			
		}catch(PDOException $e){
				$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
				return $this->response;
		}
	}
	
	
	//Filtros: grupo_id
	//Obtener todos los diganosticos por grupo
	public function getAllByGrupo(){
		try
		{
		  $this->pdo = parent::conexion();
			
		  $sql = "
					SELECT ge.*,
						e.diagnostico_id value,
						e.nombre label,
						e.nombre servicio,
						ge.nombre grupo,
						e.nombre name,
						e.diagnostico_id code
					FROM grupo_diagnostico ge
					INNER JOIN diagnostico e ON e.grupo_diagnostico_id = ge.grupo_diagnostico_id
					WHERE 
						(ge.grupo_diagnostico_id = ? OR '".$this->grupo_diagnostico_id."' IS NULL OR '".$this->grupo_diagnostico_id. "'=' ' OR '".$this->grupo_diagnostico_id. "'='null')
					ORDER BY ge.nombre,e.nombre 
		  ";  
		  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->grupo_diagnostico_id
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
            $stm = $this->pdo->prepare("SELECT *, nombre as label, grupo_diagnostico_id as value  
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


	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['grupo_diagnostico_id']);
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
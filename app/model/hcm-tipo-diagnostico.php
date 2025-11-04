<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class TipoDiagnosticoModel extends Crud
{

  /* campos de la tabla */
  public  $tipo_diagnostico_id;
  private $nombre;
  private $codigo;

  const TABLE 		= 'tipo_diagnostico'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'tipo_diagnostico_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->tipo_diagnostico_id	= $data["tipo_diagnostico_id"];
		$this->nombre 	= $data["nombre"];
		$this->codigo 	= $data["codigo"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (nombre, codigo) VALUES (?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->codigo));
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
			$sql = "UPDATE $this->table SET nombre =?, codigo = ? WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->codigo, $this->tipo_diagnostico_id));
			
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
						e.diagnostico_id code,
						e.diagnostico_id

					FROM tipo_diagnostico ge
					INNER JOIN diagnostico e ON e.tipo_diagnostico_id = ge.tipo_diagnostico_id
					WHERE 
						(ge.tipo_diagnostico_id = ? OR '".$this->tipo_diagnostico_id."' IS NULL OR '".$this->tipo_diagnostico_id. "'=' ' OR '".$this->tipo_diagnostico_id. "'='null')
					ORDER BY ge.nombre,e.nombre 
		  ";  
		  
		
		  $stm = $this->pdo->prepare($sql);

		  
		  $stm->execute(array(
			$this->tipo_diagnostico_id
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
            $stm = $this->pdo->prepare("SELECT *, nombre as label, tipo_diagnostico_id as value  
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
				$this->delete($data[$i]['tipo_diagnostico_id']);
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
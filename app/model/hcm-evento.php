<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class EventoModel extends Crud
{

  /* campos de la tabla */
  public  $evento_id;
  private $comentario;
  private $fecha_inicio;
  private $fecha_fin;
  private $medico_id;
  private $especialidad_id;
  private $proveedor_id;

  const TABLE 		= 'evento'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'evento_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->evento_id	    = $data["evento_id"];
		$this->comentario 	    = $data["comentario"];
		$this->fecha_inicio 	= $data["fecha_inicio"];
        $this->fecha_fin 	    = $data["fecha_fin"];
        $this->medico_id 	    = $data["medico_id"];
        $this->especialidad_id  = $data["especialidad_id"];
        $this->proveedor_id 	= $data["proveedor_id"];
	}
  
	public function create(){
		try{

            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (		
                                                comentario, 	
                                                fecha_inicio, 	
                                                fecha_fin, 	
                                                medico_id, 	
                                                especialidad_id,  
                                                proveedor_id 
                                              ) 
                                              VALUES (?,?,?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(	
                    $this->comentario, 	
                    $this->fecha_inicio, 	
                    $this->fecha_fin, 	
                    $this->medico_id, 	
                    $this->especialidad_id,  
                    $this->proveedor_id 
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
                            comentario =?,  	
                            fecha_inicio =?,  	
                            fecha_fin =?,  	
                            medico_id =?, 	
                            especialidad_id =?,  
                            proveedor_id 
            WHERE $this->idTableName = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                            $this->comentario, 	
                            $this->fecha_inicio, 	
                            $this->fecha_fin, 	
                            $this->medico_id, 	
                            $this->especialidad_id,  
                            $this->proveedor_id,
                            $this->evento_id	 
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
                $this->delete($data[$i]['evento_id']);
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
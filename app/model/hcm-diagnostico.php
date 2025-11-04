<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class DiagnosticoModel extends Crud
{

  /* campos de la tabla */
  public  $diagnostico_id;
  private $codigo;
  private $nombre;
  private $tipo_diagnostico_id;
  private $grupo_diagnostico_id;
  private $activo;

  const TABLE 		= 'diagnostico'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'diagnostico_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->diagnostico_id	        = $data["diagnostico_id"];
        $this->codigo 	                = $data["codigo"];
		$this->nombre 	                = $data["nombre"];
        $this->tipo_diagnostico_id 	    = $data["tipo_diagnostico_id"];
		$this->grupo_diagnostico_id 	= $data["grupo_diagnostico_id"];
        $this->activo 	                = $data["activo"];
	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (codigo, 
                                              nombre, 
                                              tipo_diagnostico_id, 
                                              grupo_diagnostico_id,
                                              activo
                                              ) 
                                            VALUES (?,?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
                                $this->codigo, 
                                $this->nombre, 
                                $this->tipo_diagnostico_id,
                                $this->grupo_diagnostico_id,
                                $this->activo
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
                                        codigo = ?, 
                                        nombre =?, 
                                        tipo_diagnostico_id=?,  
                                        grupo_diagnostico_id=?, 
                                        activo=?

                                    WHERE $this->idTableName = ?";
		 $stm = $this->pdo->prepare($sql);
		 $stm->execute(array(
                                $this->codigo, 
                                $this->nombre, 
                                $this->tipo_diagnostico_id,
                                $this->grupo_diagnostico_id,
                                $this->activo,
                                $this->diagnostico_id
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
            $stm = $this->pdo->prepare("SELECT d.*, 
                                               tp.nombre as nombre_tipo, 
                                               gp.nombre as nombre_grupo, 
                                               upper(d.nombre)  as label, d.diagnostico_id as value  

                                             FROM $this->table d
                                            INNER JOIN tipo_diagnostico tp on
                                                        tp.tipo_diagnostico_id = d.tipo_diagnostico_id
                                            INNER JOIN grupo_diagnostico gp on
                                                        gp.grupo_diagnostico_id = d.grupo_diagnostico_id
                                                     
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
                $this->delete($data[$i]['diagnostico_id']);
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
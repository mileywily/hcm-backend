<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class ProveedorModel extends Crud
{

  /* campos de la tabla */
  public  $proveedor_id;
  private $codigo;
  private $nombre;
  private $nemonico;
  private $rif;
  private $representante; 
  private $direccion;
  private $telefono;
  private $email;
  private $nit;
  public $tipo_proveedor_id;
  public $tipo_centro_id;

  
  const TABLE 		= 'proveedor'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'proveedor_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->proveedor_id	    = $data["proveedor_id"];
                $this->codigo               = $data["codigo"];
                $this->nombre               = $data["nombre"];
                $this->nemonico             = $data["nemonico"];
                $this->rif                  = $data["rif"];
                $this->representante        = $data["representante"];
                $this->direccion            = $data["direccion"];
                $this->telefono             = $data["telefono"];
                $this->email                = $data["email"];
                $this->nit                  = $data["nit"];
                $this->tipo_proveedor_id    = $data["tipo_proveedor_id"];
				$this->tipo_centro_id    = $data["tipo_centro_id"];
					$this->activo    = $data["activo"];

	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (	  
                                                codigo,     
                                                nombre,        
                                                nemonico,         
                                                rif,            
                                                representante,          
                                                direccion,   
                                                telefono,         
                                                email,   
                                                nit,            
                                                tipo_proveedor_id,
												tipo_centro_id,
												activo
                                              ) 
                                              VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(   
                                    $this->codigo,             
                                    $this->nombre,             
                                    $this->nemonico,           
                                    $this->rif,                
                                    $this->representante,      
                                    $this->direccion,          
                                    $this->telefono,           
                                    $this->email,              
                                    $this->nit,                
                                    $this->tipo_proveedor_id,
                                    $this->tipo_centro_id,	
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
            $sql = "UPDATE $this->table 
            SET  
                        codigo=?,      
                        nombre=?,         
                        nemonico=?,          
                        rif=?,            
                        representante=?,          
                        direccion=?,    
                        telefono=?,         
                        email=?,    
                        nit=?,          
                        tipo_proveedor_id=?,
						tipo_centro_id=?,
						activo =?
            WHERE $this->idTableName = ?";
            
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(  
                        $this->codigo,       
                        $this->nombre,          
                        $this->nemonico,           
                        $this->rif,              
                        $this->representante,            
                        $this->direccion,         
                        $this->telefono,             
                        $this->email,    
                        $this->nit,    
                        $this->tipo_proveedor_id,
						$this->tipo_centro_id,
						$this->activo,
                        $this->proveedor_id,	
                
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
                    $stm = $this->pdo->prepare("SELECT p.*, p.nombre as label, 
                                                       proveedor_id as value, 
                                                       t.nombre as tipo_proveedor_nombre,
													   tc.nombre as tipo_centro_nombre
                                                FROM $this->table p
                                                INNER JOIN tipo_proveedor t ON p.tipo_proveedor_id = t.tipo_proveedor_id
                                                LEFT JOIN 	tipo_centro tc ON p.tipo_centro_id = tc.tipo_centro_id											
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

        public function getAllByTipoCombo(){
                try
                {
                    $this->pdo = parent::conexion();
                    $stm = $this->pdo->prepare("SELECT p.*, p.nombre as label, 
                                                       proveedor_id as value, 
                                                       t.nombre as tipo_proveedor_nombre
                                                FROM $this->table p
                                                INNER JOIN tipo_proveedor t ON p.tipo_proveedor_id = t.tipo_proveedor_id
                                                WHERE 	p.tipo_proveedor_id =  $this->tipo_proveedor_id			
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


        public function getAllByTipoCentroCombo(){
                try
                {
                    $this->pdo = parent::conexion();
                    $stm = $this->pdo->prepare("SELECT p.*, p.nombre as label, 
                                                       proveedor_id as value, 
                                                       t.nombre as tipo_proveedor_nombre
                                                FROM $this->table p
                                                INNER JOIN tipo_centro t ON p.tipo_centro_id = t.tipo_centro_id
                                                WHERE 	p.tipo_centro_id =  $this->tipo_centro_id			
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
				$this->delete($data[$i]['proveedor_id']);
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
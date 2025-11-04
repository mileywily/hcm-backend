<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDO;
use PDOException;

use App\Lib\Crud;

class UsuarioModel extends Crud
{

  /* campos de la tabla */
  public  $usuario_id;
  private $nombre;
  private $apellido;
  private $usuario;
  private $clave;
  private $activo;

  const TABLE 		= 'usuario'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'usuario_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->tipo_solicitud_id	= $data["usuario_id"];
		$this->nombre 				= $data["nombre"];
		$this->apellido 			= $data["apellido"];
    	$this->usuario 				= $data["usuario"];
		$this->clave 				= $data["clave"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();

			$sql = "INSERT INTO $this->table (nombre, apellido, usuario, clave) VALUES (?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array($this->nombre, $this->apellido,$this->usuario, $this->clave));
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
              apellido = ?,
              usuario =?, 
              clave = ? 
            WHERE $this->idTableName = ?";
		 $stm = $this->pdo->prepare($sql);
		 $stm->execute(array($this->nombre, $this->apellido,$this->usuario, $this->clave, $this->usuario_id));
		 
		 $this->pdo = null;

		 $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
		 return $this->response;
		 
	   }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
	   }
	}


  public function Login()
  {
      try
      {

		$this->pdo = parent::conexion();	  
		$sql = " SELECT * from  $this->table where usuario = ? and clave = ? and activo = 1 ";
			

		$stm = $this->pdo->prepare($sql);
		$stm->execute(array($this->usuario, $this->clave));

		$result = $stm->fetch();

		$this->pdo = null;
		
		return $result;
    }
    catch (PDOException $e)
    {
        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_POR_ID, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
        return $this->response;
    }
 }

}
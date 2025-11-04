<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class MedicoModel extends Crud
{

  /* campos de la tabla */
  public  $medico_id;
  private $nombres;
  private $apellidos;
  private $cedula;
  private $msds; 
  private $telefono;
  private $email;
  private $horario;
  private $parroquia;
  private $direccion_consultorio;
  private $activo;

  const TABLE 		= 'medico'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'medico_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->medico_id			 = $data["medico_id"];
		$this->nombres 	    		 = $data["nombres"];
		$this->apellidos 			 = $data["apellidos"];
		$this->cedula 				 = $data["cedula"];
		$this->msds 				 = $data["msds"];
		$this->telefono 			 = $data["telefono"];
		$this->email 				 = $data["email"];
		$this->horario 				 = $data["horario"];
		$this->parroquia    		 = $data["parroquia"];
		$this->direccion_consultorio = $data["direccion_consultorio"];
		$this->activo 				 = $data["activo"];
	}
  
	public function create(){
		try{
			$this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (
						nombres, 
						apellidos,
						cedula,
						msds,
						telefono,
						email,
						horario,
						parroquia, 
						direccion_consultorio,
						activo

						) VALUES (?,?,?,?,?,?,?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
							$this->nombres, 
							$this->apellidos,
							$this->cedula, 
							$this->msds,
							$this->telefono, 
							$this->email,
							$this->horario,
							$this->parroquia,
							$this->direccion_consultorio,
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
								nombres =?, 
								apellidos = ?,
								cedula =?, 
								msds = ?,
								telefono =?, 
								email = ?,
								horario= ?,
								parroquia= ?,
								direccion_consultorio= ?,
								activo =?							

					WHERE $this->idTableName = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
								$this->nombres, 
								$this->apellidos, 
								$this->cedula,
								$this->msds, 
								$this->telefono,
								$this->email, 
								$this->horario,
								$this->parroquia,
								$this->direccion_consultorio,
								$this->activo,
								$this->medico_id
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
            $stm = $this->pdo->prepare("SELECT *, concat(nombres, ' ' , apellidos)  as label, medico_id as value  
										FROM $this->table ORDER BY nombres  ");
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
				$this->delete($data[$i]['medico_id']);
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
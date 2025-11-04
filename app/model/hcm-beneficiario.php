<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class BeneficiarioModel extends Crud
{

        /* campos de la tabla */
        public  $beneficiario_id;
        public  $cedula_titular;
        private $consecutivo;
        private $empresa_id;
        private $nombres;
        private $apellidos;
        private $fecha_nacimiento;
        private $parentesco;
        private $sexo;
        private $fecha_inclusion;
        private $telefono;
        private $direccion;
        private $ptp;
        private $estado;
        private $cedula_beneficiario;
        private $sso;
        private $nacionalidad;
        private $grupo_sanguineo;
        private $nro_nomina;
        private $discapacidad;
        private $tutor;
        private $email;
        private $telefono2;


        //parÃ¡metros para transaccionar
        private $cedula_hcm;
        private $carga_siss = null;
		
	private $URL = 'http://vmwpser1.sidor.net/beneficiarios-siss-back/public/index.php/api';


        const TABLE       = 'beneficiario';         // Nombre de la tabla fisica
        const IDNAMETABLE = 'beneficiario_id'; // nombre del id de la tabla fisica


        public function __construct($data)
        {
                parent::__construct(self::TABLE, self::IDNAMETABLE);

                $this->beneficiario_id      = $data["beneficiario_id"];
                $this->cedula_titular       = $data["cedula_titular"];
                $this->consecutivo          = $data["consecutivo"];
                $this->empresa_id           = $data["empresa_id"];
                $this->nombres              = $data["nombres"];
                $this->apellidos            = $data["apellidos"];
                $this->fecha_nacimiento     = $data["fecha_nacimiento"];
                $this->parentesco           = $data["parentesco"];
                $this->sexo                 = $data["sexo"];
                $this->fecha_inclusion      = $data["fecha_inclusion"];
                $this->telefono             = $data["telefono"];
                $this->direccion            = $data["direccion"];
                $this->ptp                  = $data["ptp"];
                $this->estado               = $data["estado"];
                $this->cedula_beneficiario  = $data["cedula_beneficiario"];
                $this->sso                  = $data["sso"];
                $this->nacionalidad         = $data["nacionalidad"];
                $this->grupo_sanguineo      = $data["grupo_sanguineo"];
                $this->nro_nomina           = $data["nro_nomina"];
                $this->discapacidad         = $data["discapacidad"];
                $this->tutor                = $data["tutor"];
                $this->email                = $data["email"];
                $this->telefono2            = $data["telefono2"];

                $this->cedula_hcm           = $data["cedula_hcm"];
                /*$this->carga_siss           = $data["carga_siss"];*/
        }

        public function create()
        {
                try {
                        $this->pdo = parent::conexion();
                        $sql = "INSERT INTO $this->table (	  
                                                cedula_titular,     
                                                consecutivo,        
                                                empresa_id,         
                                                nombres,            
                                                apellidos,          
                                                fecha_nacimiento,   
                                                parentesco,         
                                                sexo,               
                                                fecha_inclusion,    
                                                telefono,           
                                                direccion,          
                                                ptp,                
                                                estado,             
                                                cedula_beneficiario,
                                                sso,                
                                                nacionalidad,       
                                                grupo_sanguineo,    
                                                nro_nomina,         
                                                discapacidad,       
                                                tutor,
                                                email,       
                                                telefono2,
                                                cedula_hcm    
                                              ) 
                                              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $stm = $this->pdo->prepare($sql);
                        $stm->execute(array(
                                $this->cedula_titular,
                                $this->consecutivo,
                                $this->empresa_id,
                                $this->nombres,
                                $this->apellidos,
                                $this->fecha_nacimiento,
                                $this->parentesco,
                                $this->sexo,
                                $this->fecha_inclusion,
                                $this->telefono,
                                $this->direccion,
                                $this->ptp,
                                $this->estado,
                                $this->cedula_beneficiario,
                                $this->sso,
                                $this->nacionalidad,
                                $this->grupo_sanguineo,
                                $this->nro_nomina,
                                $this->discapacidad,
                                $this->tutor,
                                $this->email,
                                $this->telefono2,
                                $this->cedula_hcm

                        ));
                        $this->pdo = null;

                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se ha creado correctamente el registro", ListaCodigoMensaje::$COD_AGREGAR_REG);
                        return $this->response;
                } catch (PDOException $e) {

                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                        return $this->response;
                }
        }

        public function update()
        {
                try {

                        $this->pdo = parent::conexion();
                        $sql = "UPDATE $this->table 
            SET  
                    cedula_titular =?,        
                    consecutivo =?,           
                    empresa_id =?,            
                    nombres =?,               
                    apellidos =?,             
                    fecha_nacimiento =?,     
                    parentesco =?,            
                    sexo =?,                  
                    fecha_inclusion =?,       
                    telefono =?,              
                    direccion =?,            
                    ptp =?,                  
                    estado =?,                
                    cedula_beneficiario =?,   
                    sso =?,                   
                    nacionalidad =?,         
                    grupo_sanguineo =?,       
                    nro_nomina =?,            
                    discapacidad =?,          
                    tutor =?,
                    email =?,
                    telefono2 =?,
                    cedula_hcm =?
            WHERE $this->idTableName = ?";

                        $stm = $this->pdo->prepare($sql);
                        $stm->execute(array(
                                $this->cedula_titular,
                                $this->consecutivo,
                                $this->empresa_id,
                                $this->nombres,
                                $this->apellidos,
                                $this->fecha_nacimiento,
                                $this->parentesco,
                                $this->sexo,
                                $this->fecha_inclusion,
                                $this->telefono,
                                $this->direccion,
                                $this->ptp,
                                $this->estado,
                                $this->cedula_beneficiario,
                                $this->sso,
                                $this->nacionalidad,
                                $this->grupo_sanguineo,
                                $this->nro_nomina,
                                $this->discapacidad,
                                $this->tutor,
                                $this->email,
                                $this->telefono2,
                                $this->cedula_hcm,
                                $this->beneficiario_id

                        ));

                        $this->pdo = null;

                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG);
                        return $this->response;
                } catch (PDOException $e) {
                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                        return $this->response;
                }
        }

        public function getTitularByBeneficiario()
        {
                try {
                        $this->pdo = parent::conexion();

                        $sql = "";

                        $sql = "SELECT * FROM $this->table 
                                WHERE cedula_titular = $this->cedula_titular and consecutivo = 0
                        ";

                        $stm = $this->pdo->prepare($sql);

                        $stm->execute();

                        $this->pdo = null;

                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

                        $this->response->result = $stm->fetchAll();

                        return $this->response->result;
                } catch (PDOException $e) {
                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                        return $this->response;
                }
        }


        public function getBeneficiarioByParametros()
        {
                try {
                        $this->pdo = parent::conexion();

                        $sql = "";
                        $parametro = "";

                        if ($this->cedula_titular) {
                                $sql = "SELECT *, 
                                        case    when estado = 'AC' then 'ACTIVO'  
                                                when estado = 'FA' then 'FALLECIDO' 
                                                when estado = 'IN' then 'INACTIVO' else estado end as d_estado,
                                        nombres as beneficiario
                                FROM $this->table 
                                WHERE cedula_titular = ? 
                                ORDER BY consecutivo

                        ";

                                $parametro = $this->cedula_titular;
                        };

                        if ($this->cedula_beneficiario) {
                                $sql = "SELECT *, 
                                        case    when estado = 'AC' then 'ACTIVO'  
                                                when estado = 'FA' then 'FALLECIDO' 
                                                when estado = 'IN' then 'INACTIVO' else estado end as d_estado,
                                        nombres as beneficiario
                                FROM $this->table 
                                WHERE cedula_beneficiario = '$this->cedula_beneficiario' 
                                ORDER BY consecutivo
                                ";

                                $parametro = $this->cedula_beneficiario;
                        };

                        if ($this->apellidos) {
                                $sql = "SELECT *, 
                                        case    when estado = 'AC' then 'ACTIVO'  
                                                when estado = 'FA' then 'FALLECIDO' 
                                                when estado = 'IN' then 'INACTIVO' else estado end as d_estado,
                                                nombres as beneficiario
                                FROM $this->table 
                                WHERE apellidos like '%$this->apellidos%'
                                ORDER BY consecutivo
                                ";

                                $parametro = $this->apellidos;
                        };


                        $stm = $this->pdo->prepare($sql);

                        $stm->execute(array($parametro));

                        $this->pdo = null;

                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

                        $this->response->result = $stm->fetchAll();

                        return $this->response->result;
                } catch (PDOException $e) {
                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
                        return $this->response;
                }
        }
		
        public function curl($params, $url){
                
                $jsonDataEncoded = json_encode($params);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $remote_server_output = curl_exec ($ch);
                curl_close ($ch);

           
                return $remote_server_output;
        }

        public function getBeneficiariosSISS(){

                $url = $this->URL.'/beneficiariossiss';
                $params;
        
                if ($this->cedula_titular) {
                        $params = array(
                                'cedula_titular' => urlencode($this->cedula_titular)
                        );
                }
                
                if ($this->cedula_beneficiario) {
                        $params = array(
                                'cedula_beneficiario' => urlencode($this->cedula_beneficiario)
                        );
                }
                
                $remote_server_output = $this->curl($params, $url);

               

                ($remote_server_output) ? $result = json_decode($remote_server_output) : $result=false;

                $aux = null;
                if(!empty($result)){  $aux = $result; }

           
                return $aux ;

        }




public function updateCargaFamiliarFromSiss()
{
	try {
                $this->pdo = parent::conexion();

                $sql = "";
                $parametro = "";

                //Consultar datos de carga familiar actual en el sistema de HCM
                if ($this->cedula_titular) {
                        $sql = "SELECT *
                        FROM $this->table 
                        WHERE cedula_titular = '$this->cedula_titular'
                        ORDER BY consecutivo

                ";
                };

                if ($this->cedula_beneficiario) {
                        $sql = "SELECT *
                                FROM $this->table 
                                WHERE cedula_beneficiario = '$this->cedula_beneficiario'
                                ORDER BY consecutivo
                        ";
                };

                $stm = $this->pdo->prepare($sql);

                $stm->execute();

                $carga_actual =  $stm->fetchAll();
				
	        $this->carga_siss  = $this->getBeneficiariosSISS();

   
                //validar carga SISS para actualizar beneficiarios
                foreach ($this->carga_siss as $carga_siss) {

                        $cedula_titular      = trim($carga_siss->cedula_titular);
                        $consecutivo         = trim($carga_siss->consecutivo);
                        $empresa_id          = $carga_siss->empresa_id;
                        $nombres             = $carga_siss->nombres;
                        $apellidos           = $carga_siss->apellidos;
                        $fecha_nacimiento    = $carga_siss->fecha_nacimiento;
                        $parentesco          = $carga_siss->parentesco;
                        $sexo                = $carga_siss->sexo;
                        $fecha_inclusion     = $carga_siss->fecha_inclusion;
                        $telefono            = $carga_siss->telefono;
                        $direccion           = $carga_siss->direccion;
                        $ptp                 = $carga_siss->ptp;
                        $estado              = $carga_siss->estado;
                        $cedula_beneficiario = $carga_siss->cedula_beneficiario;

                        /*$cedula_titular      = $carga_siss['cedula_titular'];
                        $consecutivo         = $carga_siss['consecutivo'];
                        $empresa_id          = $carga_siss['empresa_id'];
                        $nombres             = $carga_siss['nombres'];
                        $apellidos           = $carga_siss['apellidos'];
                        $fecha_nacimiento    = $carga_siss['fecha_nacimiento'];
                        $parentesco          = $carga_siss['parentesco'];
                        $sexo                = $carga_siss['sexo'];
                        $fecha_inclusion     = $carga_siss['fecha_inclusion'];
                        $telefono            = $carga_siss['telefono'];
                        $direccion           = $carga_siss['direccion'];
                        $ptp                 = $carga_siss['ptp'];
                        $estado              = $carga_siss['estado'];
                        $cedula_beneficiario = $carga_siss['cedula_beneficiario'];*/


                        //if ($carga_siss['sso'] == 'S') {
                        if ($carga_siss->sso == 'S') {
                                $sso = 1;
                        } else {
                                $sso = 0;
                        }

                        /*
                        $nacionalidad        = $carga_siss['nacionalidad'];
                        $grupo_sanguineo     = $carga_siss['grupo_sanguineo'];
                        $nro_nomina          = $carga_siss['nro_nomina'];
                        $discapacidad        = $carga_siss['discapacidad'];
                        $tutor               = $carga_siss['tutor'];
                        */
                        $nacionalidad        = $carga_siss->nacionalidad;
                        $grupo_sanguineo     = $carga_siss->grupo_sanguineo;
                        $nro_nomina          = $carga_siss->nro_nomina;
                        $discapacidad        = $carga_siss->discapacidad;
                        $tutor               = $carga_siss->tutor;


                        $existe = false;
                        $beneficiario_id = null;

                        foreach ($carga_actual as $registro) {

                                if (
                                        $registro->cedula_titular == $cedula_titular  &&
                                        $registro->consecutivo == $consecutivo 
                                ) {
                                        $existe = true;
                                        $beneficiario_id = $registro->beneficiario_id;
                                        break;
                                }
                               
                        }     


                        if ($existe) {
                            
                                $sql = "UPDATE $this->table SET          
                                        empresa_id          = ?,            
                                        nombres             = ?,               
                                        apellidos           = ?,             
                                        fecha_nacimiento    = ?,     
                                        parentesco          = ?,            
                                        sexo                = ?,                  
                                        fecha_inclusion     = ?,
                                        direccion           = ?,            
                                        ptp                 = ?,                  
                                        estado              = ?,                
                                        cedula_beneficiario = ?,   
                                        sso                 = ?,                   
                                        nacionalidad        = ?,         
                                        grupo_sanguineo     = ?,       
                                        nro_nomina          = ?,            
                                        discapacidad        = ?,          
                                        tutor               = ?
				WHERE beneficiario_id       = ?";


                                        $stm = $this->pdo->prepare($sql);
                                        $stm->execute(array(

                                                        $empresa_id,
                                                        $nombres,
                                                        $apellidos,
                                                        $fecha_nacimiento,
                                                        $parentesco,
                                                        $sexo,
                                                        $fecha_inclusion,
                                                        $direccion,
                                                        $ptp,
                                                        $estado,
                                                        $cedula_beneficiario,
                                                        $sso,
                                                        $nacionalidad,
                                                        $grupo_sanguineo,
                                                        $nro_nomina,
                                                        $discapacidad,
                                                        $tutor,
                                                        $beneficiario_id

                                        ));
                        } else {
                                //verifico en la BD antes de insertar
									
                                $sql = "SELECT cedula_titular, consecutivo
                                                FROM hcm_db.beneficiario b
                                                
                                                WHERE   b.cedula_titular= $cedula_titular and b.consecutivo =  $consecutivo;                          
                                                ";


                                $stm = $this->pdo->prepare($sql);
                                $stm->execute();

                                $beneficiario =  $stm->fetch();
                                                                        
                                if (!$beneficiario){
                                //Si no existe, ingresar
                                $sql = "INSERT INTO $this->table (    
                                        cedula_titular,     
                                        consecutivo,        
                                        empresa_id,         
                                        nombres,            
                                        apellidos,          
                                        fecha_nacimiento,   
                                        parentesco,         
                                        sexo,               
                                        fecha_inclusion,    
                                        telefono,           
                                        direccion,          
                                        ptp,                
                                        estado,             
                                        cedula_beneficiario,
                                        sso,                
                                        nacionalidad,       
                                        grupo_sanguineo,    
                                        nro_nomina,         
                                        discapacidad,       
                                        tutor   
                                        ) 
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                                        $stm = $this->pdo->prepare($sql);
                                        $stm->execute(array(
                                                        $cedula_titular,
                                                        $consecutivo,
                                                        $empresa_id,
                                                        $nombres,
                                                        $apellidos,
                                                        $fecha_nacimiento,
                                                        $parentesco,
                                                        $sexo,
                                                        $fecha_inclusion,
                                                        $telefono,
                                                        $direccion,
                                                        $ptp,
                                                        $estado,
                                                        $cedula_beneficiario,
                                                        $sso,
                                                        $nacionalidad,
                                                        $grupo_sanguineo,
                                                        $nro_nomina,
                                                        $discapacidad,
                                                        $tutor

                                        ));
				}else{
					$sql = "UPDATE $this->table SET          
                                        empresa_id          = ?,            
                                        nombres             = ?,               
                                        apellidos           = ?,             
                                        fecha_nacimiento    = ?,     
                                        parentesco          = ?,            
                                        sexo                = ?,                  
                                        fecha_inclusion     = ?,
                                        direccion           = ?,            
                                        ptp                 = ?,                  
                                        estado              = ?,                
                                        cedula_beneficiario = ?,   
                                        sso                 = ?,                   
                                        nacionalidad        = ?,         
                                        grupo_sanguineo     = ?,       
                                        nro_nomina          = ?,            
                                        discapacidad        = ?,          
                                        tutor               = ?
					WHERE beneficiario_id       = ?";


                                        $stm = $this->pdo->prepare($sql);
                                        $stm->execute(array(

                                                        $empresa_id,
                                                        $nombres,
                                                        $apellidos,
                                                        $fecha_nacimiento,
                                                        $parentesco,
                                                        $sexo,
                                                        $fecha_inclusion,
                                                        $direccion,
                                                        $ptp,
                                                        $estado,
                                                        $cedula_beneficiario,
                                                        $sso,
                                                        $nacionalidad,
                                                        $grupo_sanguineo,
                                                        $nro_nomina,
                                                        $discapacidad,
                                                        $tutor,
                                                        $beneficiario_id

                                        ));
									
				}
                        }
				
		}


                //Consultar carga actualizada para retornar consulta

                $sql = "";

                if ($this->cedula_titular) {
                        $sql = "SELECT distinct *, 
                                case    when estado = 'AC' then 'ACTIVO'  
                                        when estado = 'FA' then 'FALLECIDO' 
                                        when estado = 'IN' then 'INACTIVO' else estado end as d_estado,
                                
                                nombres as beneficiario 
                        FROM $this->table 
                        WHERE cedula_titular = '$this->cedula_titular' and estado='AC'
                        ORDER BY consecutivo

                ";
                };

                if ($this->cedula_beneficiario) {
                        $sql = "SELECT *, 
                                case    when estado = 'AC' then 'ACTIVO'  
                                        when estado = 'FA' then 'FALLECIDO' 
                                        when estado = 'IN' then 'INACTIVO' else estado end as d_estado,
                                nombres as beneficiario
                        FROM $this->table 
                        WHERE cedula_beneficiario = '$this->cedula_beneficiario' and estado='AC'
                        ORDER BY consecutivo
                        ";
                };

                if ($this->apellidos) {
                        $sql = "SELECT *, 
                                case    when estado = 'AC' then 'ACTIVO'  
                                        when estado = 'FA' then 'FALLECIDO' 
                                        when estado = 'IN' then 'INACTIVO' else estado end as d_estado,
                                        nombres as beneficiario

                        FROM $this->table 
                        WHERE apellidos like '%$this->apellidos%' and estado='AC'
                        ORDER BY consecutivo
                        ";
                };


                $stm = $this->pdo->prepare($sql);

                $stm->execute();

                $this->pdo = null;

                $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

                $this->response->result = $stm->fetchAll();

                return $this->response->result;

        } catch (PDOException $e) {
                echo $e;
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, 'Error al actualizar datos del(los) beneficiario(s)', ListaCodigoMensaje::$COD_ERROR);

                return $this->response;
        }
}

public function updateSiss()
{

	try 
	{
		$this->pdo = parent::conexion();
		$sql = "";

		//Consultar datos de carga familiar actual en el sistema de HCM
		//
		if ($this->cedula_titular) 
		{
			$sql = "SELECT 
						* 
					FROM 
						$this->table 
					WHERE 
						cedula_titular = :cedula_titular 
					ORDER BY 
						consecutivo";
		};
		
		$stm = $this->pdo->prepare($sql);
		$stm->bindParam(':cedula_titular', $this->cedula_titular);
        $stm->execute();
		
		$carga_actual = $stm->fetchAll();
        $this->pdo = null;

        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

        $this->response->result = $carga_actual;
		return $this->response->result;
    } catch (PDOException $e) {
                
		$this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, 'Error al actualizar datos del(los) beneficiario(s)', ListaCodigoMensaje::$COD_ERROR);

		return $this->response;
    }
}








}

<?php


namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class SolicitudModel extends Crud
{

  /* campos de la tabla */
  public  $solicitud_id;
  private $fecha_solicitud;
  public  $beneficiario_id;
  private $motivo;
  private $email;
  private $telefono1; 
  private $telefono2;
  private $tipo_solicitud_id;
  private $especialidad_id;
  private $medico_id;
  private $origen_id;
  private $estado_solicitud_id;
  private $causa_solicitud_id;
  private $tipo_cobertura_id;
  private $observacion;
  private $usuario_creador;
  private $nombre_creador;
  private $usuario_modificador;
  private $nombre_modificador;
  private $modified;
  private $domicilio_id;
  private $diagnostico_id;
  private $cedula_hcm;
  private $fecha_recepcion;
  private $tipo_atencion_id;
  private $servicios;
  private $examenes;
  private $diagnosticos;
  private $prioridad_id;
  private $ente_id;

  

  const TABLE 		= 'solicitud'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'solicitud_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

        $this->solicitud_id         = $data["solicitud_id"];
        $this->fecha_solicitud      = $data["fecha_solicitud"];
        $this->beneficiario_id      = $data["beneficiario_id"];
        $this->motivo               = $data["motivo"];
        $this->email                = $data["email"];
        $this->telefono1            = $data["telefono1"];
        $this->telefono2            = $data["telefono2"];
        $this->tipo_solicitud_id    = $data["tipo_solicitud_id"];
        $this->especialidad_id      = $data["especialidad_id"];
        $this->medico_id            = $data["medico_id"];
        $this->origen_id            = $data["origen_id"];
        $this->estado_solicitud_id  = $data["estado_solicitud_id"];
        $this->causa_solicitud_id   = $data["causa_solicitud_id"];
        $this->tipo_cobertura_id    = $data["tipo_cobertura_id"];
        $this->observacion          = $data["observacion"];
        $this->usuario_creador      = $data["usuario_creador"];
        $this->nombre_creador       = $data["nombre_creador"];
        $this->usuario_modificador  = $data["usuario_modificador"];
        $this->nombre_modificador   = $data["nombre_modificador"];
        $this->modified             = $data["modified"];
        $this->domicilio_id         = $data["domicilio_id"];

        $this->cedula_hcm           = $data["cedula_hcm"];
		$this->fecha_recepcion      = $data["fecha_recepcion"];
        $this->tipo_atencion_id     = $data["tipo_atencion_id"];
        $this->servicios            = $data["servicios"];
		$this->procedimientos       = $data["procedimientos"];
        $this->examenes             = $data["examenes"];
		$this->diagnosticos         = $data["diagnosticos"];
		$this->diagnostico_id       = $data["diagnostico_id"];
        $this->prioridad_id       = $data["prioridad_id"];
		$this->ente_id            = $data["ente_id"];
	}
  
	public function create(){
		try{

            $this->pdo = parent::conexion();

			$sql = "INSERT INTO $this->table (     
                                                   
                            fecha_solicitud,
                            beneficiario_id,
                            motivo,
                            email,
                            telefono1,
                            telefono2,
                            tipo_solicitud_id,
                            especialidad_id,
                            medico_id,
                            origen_id,
                            estado_solicitud_id,
                            causa_solicitud_id,
                            tipo_cobertura_id,
                            observacion,
                            usuario_creador,
                            nombre_creador,
                            usuario_modificador,
                            nombre_modificador,
                            cedula_hcm,
                            fecha_recepcion,
                            tipo_atencion_id,
						    domicilio_id,
							diagnostico_id,
							prioridad_id,
							ente_id
                    ) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

   		    $stm = $this->pdo->prepare($sql);

			$stm->execute(array(     
                $this->fecha_solicitud,
                $this->beneficiario_id,
                $this->motivo,
                $this->email,
                $this->telefono1,
                $this->telefono2,
                $this->tipo_solicitud_id,
                $this->especialidad_id,
                $this->medico_id,
                $this->origen_id,
                $this->estado_solicitud_id,
                $this->causa_solicitud_id,
                $this->tipo_cobertura_id,
                $this->observacion,
                $this->usuario_creador,
                $this->nombre_creador,
                $this->usuario_modificador,
                $this->nombre_modificador,
                $this->cedula_hcm,
                $this->fecha_recepcion,
                $this->tipo_atencion_id,
                $this->domicilio_id,
				$this->diagnostico_id,
				$this->prioridad_id,
				$this->ente_id
            ));


            $stm = $this->pdo->prepare("SELECT LAST_INSERT_ID() as solicitud_id");
            $stm->execute();

            $this->responsefull->result = $stm->fetch();

            $id = $this->responsefull->result->solicitud_id;

            $stm = $this->pdo->prepare("SELECT * FROM $this->table WHERE $this->idTableName = $id");
            $stm->execute(array($id));

            $result= $stm->fetch($this->pdo::FETCH_OBJ);
			

			
            //agrega los adicionales a una solicitud 
			
			
			$this->add_adictional_solicitud($id);
            $this->pdo = null;

            $this->responsefull->setResponse(
                ListaCodigoMensaje::$ACCION_AGREGAR_REG, 
                "Se ha creado correctamente el registro", 
                ListaCodigoMensaje::$COD_AGREGAR_REG, $result);


         	return $this->responsefull;
		 
	   }catch(PDOException $e){
		
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
			return $this->response;
	   }
	}
	
	public function add_adictional_solicitud_services($id)
	{

		
				//Para tipo atención CONSULTA, se añade el servicio de CONSULTA a la tabla de servicios solicitados
         if ($this->tipo_atencion_id == 2)
		 {
                $sql = "INSERT INTO solicitud_servicio (
                    solicitud_id,
                    servicio_id
                ) 
                VALUES (?,?)";

                $stm = $this->pdo->prepare($sql);
                $stm->execute(array(
                    $id, 
                    1
                ));
          }

            //Para tipo atención LABORATORIO, se añade el servicio de EXAMENES DE LABORATORIO a la tabla de servicios solicitados
          if ($this->tipo_atencion_id == 4)
		  {
                $sql = "INSERT INTO solicitud_servicio (
                    solicitud_id,
                    servicio_id
                ) 
                VALUES (?,?)";

                $stm = $this->pdo->prepare($sql);
                $stm->execute(array(
                    $id, 
                    17
                ));
            }
			
		
			if ($this->servicios)
			{

				$sql = " delete from  solicitud_servicio  where solicitud_id = ? and servicio_id not in (1,17) ";
				$stm = $this->pdo->prepare($sql);
				$stm->execute(array($id));
		
				foreach($this->servicios as $servicio) 
				{ 
					$sql = "INSERT INTO solicitud_servicio (
							solicitud_id,
							servicio_id
						) 
						VALUES (?,?)";
		
						$stm = $this->pdo->prepare($sql);
						$stm->execute(array(
							$id, 
							$servicio['servicio_id']
						));
				}
				
			}
		
	}
	
	
	public function add_adictional_solicitud_procedimientos($id)
	{
			if ($this->procedimientos)
			{

				$sql = " delete from  solicitud_presupuesto  where solicitud_id = ? and servicio_id not in (1,17) ";
				$stm = $this->pdo->prepare($sql);
				$stm->execute(array($id));
		
				foreach($this->procedimientos as $procedimiento) 
				{ 
					$sql = "INSERT INTO solicitud_presupuesto (
							solicitud_id,
							servicio_id,
							proveedor_id,
							precio_dolar,
							precio_bs,
							precio_tasa,
							activo
						) 
						VALUES (?,?,?,?,?,?,?)";
		
						$stm = $this->pdo->prepare($sql);
						$stm->execute(array(
							$id, 
							$procedimiento['servicio_id'],
							$procedimiento['proveedor_id'],
							$procedimiento['precio'],
							$procedimiento['precio_bs'],
							$procedimiento['precio_tasa'],0
						));
				}
				
			}
		
	}
	
	public function add_adictional_solicitud_examenes($id)
	{
		        if ($this->examenes){
			    $sql = " delete from  solicitud_examen  where solicitud_id = ? ";
				  
				  $stm = $this->pdo->prepare($sql);
				  $stm->execute(array(
				  $id));
				  
                foreach($this->examenes as $examen) { 
                    $sql = "INSERT INTO solicitud_examen (
                        solicitud_id,
                        examen_id
                    ) 
                    VALUES (?,?)";
    
                    $stm = $this->pdo->prepare($sql);
                    $stm->execute(array(
                        $id , 
                        $examen['examen_id']
                    ));
                }
            }
		
	}
	
	
	
	public function add_adictional_solicitud_diagnosticos($id)
	{
		        $i=0;
		        if ($this->diagnosticos){
			    $sql = " delete from  solicitud_diagnostico  where solicitud_id = ? ";
				  
				  $stm = $this->pdo->prepare($sql);
				  $stm->execute(array(
				  $id));
				  
                foreach($this->diagnosticos as $diagnostico) 
				{ 
                   
                   if ($i ==0 )
				   {
					   $this->update_diagnostico_solicitud($id,$diagnostico['diagnostico_id']);
				   }
				   
				   $sql = "INSERT INTO solicitud_diagnostico (
                        solicitud_id,
                        diagnostico_id
                    ) 
                    VALUES (?,?)";
    
                    $stm = $this->pdo->prepare($sql);
                    $stm->execute(array(
                        $id , 
                        $diagnostico['diagnostico_id']
                    ));
					
					$i++;
                }
            }
		
	}
	
	public function update_diagnostico_solicitud($id,$diagnostico_id)
	{
		        
                $sql = "update solicitud set diagnostico_id = ? where solicitud_id = ? ";

                $stm = $this->pdo->prepare($sql);
                $stm->execute(array(
                    $diagnostico_id, $id
                ));
            
	}
	
	
	public function add_adictional_solicitud($id)
	{
            //Se agregan los servicios requeridos en la solicitud caso CONSULTA, ESTUDIOS ESPECIALES e IMAGENES
			$this->add_adictional_solicitud_services($id);
			
			//Se agregan los presupuestos  de procedimientos  en la solicitud caso QUIRURGICOS
			$this->add_adictional_solicitud_procedimientos($id);

            //Se agregan los examenes requeridos en la solicitud caso LABORATORIO
			$this->add_adictional_solicitud_examenes($id);
			
			  //Se agregan los diagnosticos relacionados en la solicitud 
			$this->add_adictional_solicitud_diagnosticos($id);

		
	}
	
	

	public function update(){
		try{
            $this->pdo = parent::conexion();
            $sql = "UPDATE $this->table 
            SET    
                fecha_solicitud=?,	   
                beneficiario_id=?,	      
                motivo=?,	               
                email=?,	                
                telefono1=?,	           
                telefono2=?,	            
                tipo_solicitud_id=?,	 
                especialidad_id=?,	
                medico_id=?,	
                origen_id=?,	            
                estado_solicitud_id=?,	
                causa_solicitud_id=?,	
                tipo_cobertura_id=?,	
                observacion=?,
                usuario_modificador=?,
                nombre_modificador=?,
                modified=?,
                cedula_hcm=?,
                fecha_recepcion=?, 
                tipo_atencion_id=?,
				domicilio_id=?,
				diagnostico_id =?,
				prioridad_id =?,
				ente_id = ?
            WHERE $this->idTableName = ?";
            
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(   
                $this->fecha_solicitud,
                $this->beneficiario_id,             
                $this->motivo,          
                $this->email,    
                $this->telefono1,       
                $this->telefono2, 
                $this->tipo_solicitud_id,   
                $this->especialidad_id,  
                $this->medico_id,           
                $this->origen_id,         
                $this->estado_solicitud_id, 
                $this->causa_solicitud_id, 
                $this->tipo_cobertura_id,     
                $this->observacion,
                $this->$usuario_modificador,
                $this->$nombre_modificador,
                $this->modified,  
                $this->cedula_hcm,  
                $this->fecha_recepcion,  
                $this->tipo_atencion_id, 
				$this->domicilio_id,
				$this->diagnostico_id,
				$this->prioridad_id,
				$this->ente_id,
                $this->solicitud_id    
                ));
				

				
				//Se agregan los servicios requeridos en la solicitud caso CONSULTA, ESTUDIOS ESPECIALES e IMAGENES
				$this->add_adictional_solicitud_services($this->solicitud_id);
				
				//Presupuestos de Procedimientos en la solicitud caso Quirurgica
				$this->add_adictional_solicitud_procedimientos($this->solicitud_id);

                //Se agregan los examenes requeridos en la solicitud caso LABORATORIO
			    $this->add_adictional_solicitud_examenes($this->solicitud_id);
				
				//Se agregan los diagnosticos relacionados en la solicitud 
			    $this->add_adictional_solicitud_diagnosticos($this->solicitud_id);
				

            
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
            return $this->response;
		 
	   }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
	   }
	}

    public function cancelarSolicitud(){
        try
        {
             $this->pdo = parent::conexion();
 
             //coloco la solicitud como cancelada
             $sql = "
             UPDATE solicitud SET 
                     estado_solicitud_id = 3,
                     causa_solicitud_id =  $this->causa_solicitud_id,
                     usuario_modificador = '$this->usuario_modificador',
                     nombre_modificador  = '$this->nombre_modificador'
             WHERE   solicitud_id        =  $this->solicitud_id  ";
             
             $stm = $this->pdo->prepare($sql);
             $stm->execute();
 
             $this->pdo = null;
             $this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Se ha cancelado la solicitud.", ListaCodigoMensaje::$COD_CANCELAR_REG ); 
             return $this->response;
                 
         }catch(PDOException $e){                         
             $this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
             return $this->response;
        }
     }
	 
	 
	 public function enviarSolicitud(){
        try
        {
             $this->pdo = parent::conexion();
 
             //coloco la solicitud como cancelada
             $sql = "
             UPDATE solicitud SET 
                     estado_solicitud_id = 5,
                     ente_id=2,
                     usuario_modificador = '$this->usuario_modificador',
                     nombre_modificador  = '$this->nombre_modificador'
             WHERE   solicitud_id        =  $this->solicitud_id  ";
             
             $stm = $this->pdo->prepare($sql);
             $stm->execute();
 
             $this->pdo = null;
             $this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Se ha enviado la solicitud.", ListaCodigoMensaje::$COD_CANCELAR_REG ); 
             return $this->response;
                 
         }catch(PDOException $e){                         
             $this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
             return $this->response;
        }
     }
	 
	 
	 
	 
	 


    public function getSolicitudesByBeneficiario(){
        try
        {

                $this->pdo = parent::conexion();

                $sql=""; 

                $sql="SELECT    s.*,  
                                b.cedula_titular, 
                                b.cedula_beneficiario, 
                                b.parentesco, b.nombres as nombre_beneficiario, 
                                b.apellidos as apellido_beneficiario,
                                t.nombres as nombre_titular, 
                                t.apellidos as apellido_titular,
                                tc.nombre as nombre_tipo_cobertura,
                                ts.nombre as nombre_tipo_solicitud,
                                es.nombre as nombre_estado_solicitud,
                                c.motivo as nombre_causa_solicitud,
                                ep.nombre as nombre_especialidad,
                                ta.nombre as nombre_tipo_atencion,
								dom.descripcion as nombre_domicilio,
								dg.descripcion as nombre_diagnostico
                FROM $this->table s
                inner join beneficiario b on s.beneficiario_id = b.beneficiario_id
                inner join beneficiario t on (b.cedula_titular =t.cedula_titular and t.consecutivo=0)
                inner join tipo_cobertura tc on s.tipo_cobertura_id = tc.tipo_cobertura_id 
                inner join tipo_solicitud ts on s.tipo_solicitud_id = ts.tipo_solicitud_id 
                inner join estado_solicitud es on s.estado_solicitud_id = es.estado_solicitud_id  
                inner join especialidad ep on s.especialidad_id = ep.especialidad_id  
                inner join tipo_atencion ta on s.tipo_atencion_id = ta.tipo_atencion_id  
                left  join causa_solicitud c on s.causa_solicitud_id = c.causa_solicitud_id
                left  join domicilio dom 	on  s.domicilio_id = dom.domicilio_id			
                left  join diagnostico dg on s.diagnostico_id = dg.diagnostico_id
                WHERE s.beneficiario_id = $this->beneficiario_id AND ( s.tipo_atencion_id = $this->tipo_atencion_id
				or  s.tipo_solicitud_id = $this->tipo_solicitud_id )

                ORDER BY s.solicitud_id DESC
                ";  

               

                $stm = $this->pdo->prepare($sql);

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

    
    public function getSolicitudesAbiertasByBeneficiario(){
        try
        {

                $this->pdo = parent::conexion();

                $sql=""; 

                $sql="SELECT    s.*,  
                                b.cedula_titular, 
                                b.cedula_beneficiario, 
                                b.parentesco, b.nombres as nombre_beneficiario, 
                                b.apellidos as apellido_beneficiario,
                                t.nombres as nombre_titular, 
                                t.apellidos as apellido_titular,
                                tc.nombre as nombre_tipo_cobertura,
                                ts.nombre as nombre_tipo_solicitud,
                                es.nombre as nombre_estado_solicitud,
                                c.motivo as nombre_causa_solicitud,
                                ep.nombre as nombre_especialidad,
                                ta.nombre as nombre_tipo_atencion,
								dg.descripcion as nombre_diagnostico
                FROM $this->table s
                inner join beneficiario b on s.beneficiario_id = b.beneficiario_id
                inner join beneficiario t on (b.cedula_titular =t.cedula_titular and t.consecutivo=0)
                inner join tipo_cobertura tc on s.tipo_cobertura_id = tc.tipo_cobertura_id 
                inner join tipo_solicitud ts on s.tipo_solicitud_id = ts.tipo_solicitud_id 
                inner join estado_solicitud es on s.estado_solicitud_id = es.estado_solicitud_id 
                inner join especialidad ep on s.especialidad_id = ep.especialidad_id  
                inner join tipo_atencion ta on s.tipo_atencion_id = ta.tipo_atencion_id 
                left  join causa_solicitud c on s.causa_solicitud_id = c.causa_solicitud_id   
				left  join diagnostico dg on s.diagnostico_id = dg.diagnostico_id

                WHERE s.beneficiario_id = $this->beneficiario_id and es.estado_solicitud_id  in (1)

                ORDER BY s.solicitud_id DESC
                ";  

               

                $stm = $this->pdo->prepare($sql);

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


    public function getSolicitudesByTitularCantidad()
    {
        try
        {
            $this->pdo = parent::conexion();
        

            $sql = "
                        SELECT cedula_titular 
                        FROM hcm_db.beneficiario 
                        WHERE beneficiario_id = $this->beneficiario_id";

                       // echo $sql;
        
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            $titular =  $stm->fetch();

    
            $sql=""; 
            
            $sql = "
                    SELECT 
                    b.cedula_titular, 
                    EXTRACT(YEAR FROM s.created) AS anio,
                    EXTRACT(MONTH FROM s.created) AS mes, 
                    COUNT(s.solicitud_id) AS total_solicitudes 
                FROM 
                    solicitud s 
                inner JOIN 
                    beneficiario b ON s.beneficiario_id = b.beneficiario_id 
                 WHERE 
                    EXTRACT(YEAR FROM s.created) = EXTRACT(YEAR FROM CURRENT_DATE )
                    and EXTRACT(MONTH FROM s.created) = EXTRACT(MONTH FROM CURRENT_DATE )
                    and s.tipo_solicitud_id = 1
                    and b.cedula_titular  = ". $titular->cedula_titular .
                "  GROUP BY 
                    b.cedula_titular, anio, mes 
                HAVING 
                    COUNT(s.solicitud_id) >= 5 
                ORDER BY 
                    anio,b.cedula_titular";
                //     echo $sql;

                    $stm = $this->pdo->prepare($sql);

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
	


    public function getSolicitudesByTitular(){
        try
        {
            $this->pdo = parent::conexion();

            $sql = "
                        SELECT cedula_titular 
                        FROM hcm_db.beneficiario 
                        WHERE beneficiario_id = $this->beneficiario_id";
        
            $stm = $this->pdo->prepare($sql);
            $stm->execute();

            $titular =  $stm->fetch();

    
            $sql=""; 



            $sql="SELECT    s.*,  
                            b.cedula_titular, 
                            b.cedula_beneficiario, 
                            b.parentesco, b.nombres as nombre_beneficiario, 
                            b.apellidos as apellido_beneficiario,
                            t.nombres as nombre_titular, 
                            t.apellidos as apellido_titular,
                            tc.nombre as nombre_tipo_cobertura,
                            ts.nombre as nombre_tipo_solicitud,
                            es.nombre as nombre_estado_solicitud,
                            c.motivo as nombre_causa_solicitud,
                            ep.nombre as nombre_especialidad,
                            ta.nombre as nombre_tipo_atencion
            FROM $this->table s
            inner join beneficiario b on s.beneficiario_id = b.beneficiario_id
            inner join beneficiario t on (b.cedula_titular = t.cedula_titular and b.consecutivo = t.consecutivo)
            inner join tipo_cobertura tc on s.tipo_cobertura_id = tc.tipo_cobertura_id 
            inner join tipo_solicitud ts on s.tipo_solicitud_id = ts.tipo_solicitud_id 
            inner join estado_solicitud es on s.estado_solicitud_id = es.estado_solicitud_id  
            left  join causa_solicitud c on s.causa_solicitud_id = c.causa_solicitud_id  
            inner join especialidad ep on s.especialidad_id = ep.especialidad_id  
            inner join tipo_atencion ta on s.tipo_atencion_id = ta.tipo_atencion_id 
            WHERE  b.cedula_titular  = '$titular->cedula_titular' AND (
                ($this->tipo_atencion_id IS NOT NULL AND s.tipo_atencion_id = $this->tipo_atencion_id)
                OR
                ($this->tipo_atencion_id in (6,7,8,9,10,11,12,19,20) AND s.tipo_solicitud_id = $this->tipo_solicitud_id)
            )

            ORDER BY s.solicitud_id DESC
            ";  

             




            $stm = $this->pdo->prepare($sql);

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
				$this->delete($data[$i]['solicitud_id']);
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
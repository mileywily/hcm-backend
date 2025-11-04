<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;
use App\Model\CuposModel;

class AtencionModel extends Crud
{

  /* campos de la tabla */
  public  $atencion_id;
  private $solicitud_id;
  private $tipo_atencion_id;
  private $prioridad_id;
  private $tipo_consulta_id;
  private $servicio_id;
  private $motivo;
  private $medico_id;
  private $especialidad_id; 
  private $sitio_id; 
  private $proveedor_id;
  private $estado_atencion_id;
  private $causa_id;
  private $is_cita;
  private $fecha_cita;
  private $fecha_ingreso;
  private $fecha_egreso; 
  private $observaciones;
  private $fecha_atencion;
  private $diagnostico_id;
  private $modified;

  private $usuario_creador;
  private $nombre_creador;
  private $usuario_modificador;
  private $nombre_modificador;

  //variable para transaccionar
  private $cupo_id;
  private $turno_id;
  private $servicios;
  private $examenes;

  private $beneficiario_id;
  

  const TABLE 	    = 'atencion'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'atencion_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

                $this->atencion_id	      = $data["atencion_id"];
                $this->solicitud_id           = $data["solicitud_id"];
                $this->tipo_atencion_id       = $data["tipo_atencion_id"];
                $this->prioridad_id           = $data["prioridad_id"];
                $this->tipo_consulta_id       = $data["tipo_consulta_id"];
                $this->servicio_id            = $data["servicio_id"];
                $this->motivo                 = $data["motivo"];
                $this->medico_id              = $data["medico_id"];
                $this->especialidad_id        = $data["especialidad_id"];
                $this->sitio_id               = $data["sitio_id"];
                $this->proveedor_id           = $data["proveedor_id"];
                $this->estado_atencion_id     = $data["estado_atencion_id"];
                $this->causa_id               = $data["causa_id"];
                $this->is_cita                = $data["is_cita"];
                $this->fecha_cita             = $data["fecha_cita"];
                $this->fecha_ingreso          = $data["fecha_ingreso"];
                $this->fecha_egreso           = $data["fecha_egreso"];
                $this->observaciones          = $data["observaciones"];
                $this->fecha_atencion         = $data["fecha_atencion"];
                $this->diagnostico_id         = $data["diagnostico_id"];
                $this->modified               = $data["modified"];

                $this->usuario_creador        = $data["usuario_creador"];
                $this->nombre_creador         = $data["nombre_creador"];
                $this->usuario_modificador    = $data["usuario_modificador"];
                $this->nombre_modificador     = $data["nombre_modificador"];

                $this->cupo_id                = $data["cupo_id"];
                $this->turno_id               = $data["turno_id"];
                $this->servicios              = $data["servicios"];
                $this->examenes               = $data["examenes"];

                $this->beneficiario_id        = $data["beneficiario_id"];

                
	}

        public function create(){
                
	        try{
                         $this->pdo = parent::conexion();

			$sql = "INSERT INTO $this->table (
                                                solicitud_id,
                                                tipo_atencion_id,
                                                prioridad_id,
                                                tipo_consulta_id,
                                                servicio_id,
                                                motivo,
                                                medico_id,
                                                especialidad_id, 
                                                sitio_id, 
                                                proveedor_id,
                                                estado_atencion_id,
                                                is_cita,
                                                fecha_cita,
                                                fecha_ingreso,
                                                fecha_egreso, 
                                                observaciones,
                                                fecha_atencion,
                                                diagnostico_id,
                                                usuario_creador,
                                                nombre_creador,
                                                usuario_modificador,
                                                nombre_modificador,
                                                turno_id,
                                                beneficiario_id
                                              ) 
                                              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(  
                                        $this->solicitud_id,      
                                        $this->tipo_atencion_id,
                                        $this->prioridad_id,
                                        $this->tipo_consulta_id,
                                        $this->servicio_id,
                                        $this->motivo,             
                                        $this->medico_id,          
                                        $this->especialidad_id,    
                                        $this->sitio_id,  
                                        $this->proveedor_id,     
                                        $this->estado_atencion_id, 
                                        $this->is_cita,            
                                        $this->fecha_cita,         
                                        $this->fecha_ingreso,      
                                        $this->fecha_egreso,       
                                        $this->observaciones,      
                                        $this->fecha_atencion,     
                                        $this->diagnostico_id,

                                        $this->usuario_creador,
                                        $this->nombre_creador,
                                        $this->usuario_modificador,
                                        $this->nombre_modificador,
                                        $this->turno_id,
                                        $this->beneficiario_id
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
                                solicitud_id=?, 
                                tipo_atencion_id=?, 
                                prioridad_id=?, 
                                tipo_consulta_id=?, 
                                servicio_id=?, 
                                motivo=?, 
                                medico_id=?, 
                                especialidad_id=?,  
                                sitio_id=?,  
                                proveedor_id=?, 
                                estado_atencion_id=?, 
                                is_cita=?, 
                                fecha_cita=?, 
                                fecha_ingreso=?, 
                                fecha_egreso=?,  
                                observaciones=?, 
                                fecha_atencion=?, 
                                diagnostico_id=?, 
                                usuario_creador=?, 
                                nombre_creador=?, 
                                usuario_modificador=?, 
                                nombre_modificador=?,
                                turno_id=?,
                                beneficiario_id,
                                modified=now()
                                
                        WHERE $this->idTableName = ?";
         
		 $stm = $this->pdo->prepare($sql);
		 $stm->execute(array(
                        $this->solicitud_id,      
                        $this->tipo_atencion_id,
                        $this->prioridad_id, 
                        $this->tipo_consulta_id,
                        $this->servicio_id,
                        $this->motivo,             
                        $this->medico_id,          
                        $this->especialidad_id,    
                        $this->sitio_id,  
                        $this->proveedor_id,     
                        $this->estado_atencion_id, 
                        $this->is_cita,            
                        $this->fecha_cita,         
                        $this->fecha_ingreso,      
                        $this->fecha_egreso,       
                        $this->observaciones,      
                        $this->fecha_atencion,     
                        $this->diagnostico_id,
                        $this->usuario_creador,
                        $this->nombre_creador,
                        $this->usuario_modificador,
                        $this->nombre_modificador,   
                        $this->turno_id,   
                        $this->beneficiario_id, 
                        $this->atencion_id   
            ));
		 
		 $this->pdo = null;

		 $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
		 return $this->response;
		 
	   }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
	   }
	}


        public function crearOrdenesServicio(){
           if ($this->solicitud_id){
	           try{
                        $this->pdo = parent::conexion();
   
                        $um_cupo= new CuposModel(null);
   
                        $cupo = $um_cupo->getById($this->cupo_id);
   
                        //Verificar si existe cupo al momento de guardar
   
                        if (($cupo->cupos - $cupo->cupos_asignados) >0){
                          
                           $cupo->cupos_asignados = $cupo->cupos_asignados + 1;     
   
                           //Si existe cupo, descuenta antes de crear la orden
                           $sql = "
                           UPDATE cupos SET 
						      cupos  = $cupo->cupos,
                              cupos_asignados = $cupo->cupos_asignados
                           WHERE cupo_id = $cupo->cupo_id";
                       
                       
                           $stm = $this->pdo->prepare($sql);
                           $stm->execute();
   
                           //Crear la orde de atención
   
                           $sql = "INSERT INTO $this->table (
                                   solicitud_id,
                                   tipo_atencion_id,
                                   prioridad_id,
                                   tipo_consulta_id,
                                   servicio_id,
                                   motivo,
                                   medico_id,
                                   especialidad_id, 
                                   sitio_id, 
                                   proveedor_id,
                                   estado_atencion_id,
                                   is_cita,
                                   fecha_cita,
                                   fecha_ingreso,
                                   fecha_egreso, 
                                   observaciones,
                                   fecha_atencion,
                                   diagnostico_id,
                                   usuario_creador,
                                   nombre_creador,
                                   usuario_modificador,
                                   nombre_modificador,
                                   turno_id,
                                   beneficiario_id 
                                 ) 
                                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
   
                           $stm = $this->pdo->prepare($sql);
                           $stm->execute(array(  
                                   $this->solicitud_id,      
                                   $this->tipo_atencion_id,
                                   $this->prioridad_id,
                                   $this->tipo_consulta_id,
                                   $this->servicio_id,
                                   $this->motivo,             
                                   $this->medico_id,          
                                   $this->especialidad_id,    
                                   $this->sitio_id,  
                                   $this->proveedor_id,     
                                   $this->estado_atencion_id, 
                                   $this->is_cita,            
                                   $this->fecha_cita,         
                                   $this->fecha_ingreso,      
                                   $this->fecha_egreso,       
                                   $this->observaciones,      
                                   $this->fecha_atencion,     
                                   $this->diagnostico_id,
   
                                   $this->usuario_creador,
                                   $this->nombre_creador,
                                   $this->usuario_modificador,
                                   $this->nombre_modificador,
                                   $this->turno_id,
                                   $this->beneficiario_id
                                 ));


                                 //if ($this->tipo_atencion_id == 4 ){
                                        $stm = $this->pdo->prepare("SELECT LAST_INSERT_ID() as atencion_id");
                                        $stm->execute();
                        
                                        $this->responsefull->result = $stm->fetch();
                        
                                        $id = $this->responsefull->result->atencion_id;
                                 //}

   
                           //Si es una orden de consulta (tipo_atencion_id=2) verificar si tiene ordenes 
                           //de estudios especiales que se deban generar en automático
   
                           if ($this->tipo_atencion_id == 2 ){

                                   //Se deben crear las ordenes en automático que correspondan a las consultas vestidas

                                if (count($this->servicios) && count($this->servicios) > 0){
                                        foreach($this->servicios as $servicio) { 
                                                //$this->tipo_consulta=null;
                                                $this->tipo_atencion_id     = $servicio['tipo_atencion_id'];
                                                $this->servicio_id          = $servicio['servicio_id'];
                                                $this->is_automatica        = 1;
                
                                                $sql = "INSERT INTO $this->table (
                                                        solicitud_id,
                                                        tipo_atencion_id,
                                                        prioridad_id,
                                                        tipo_consulta_id,
                                                        servicio_id,
                                                        motivo,
                                                        medico_id,
                                                        especialidad_id, 
                                                        sitio_id, 
                                                        proveedor_id,
                                                        estado_atencion_id,
                                                        is_cita,
                                                        is_automatica,
                                                        fecha_cita,
                                                        fecha_ingreso,
                                                        fecha_egreso, 
                                                        observaciones,
                                                        fecha_atencion,
                                                        diagnostico_id,
                                                        usuario_creador,
                                                        nombre_creador,
                                                        usuario_modificador,
                                                        nombre_modificador,
                                                        turno_id,
                                                        beneficiario_id
                                                        ) 
                                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        
                                                        $stm = $this->pdo->prepare($sql);
                                                        $stm->execute(array(  
                                                                $this->solicitud_id,      
                                                                $this->tipo_atencion_id,
                                                                $this->prioridad_id,
                                                                $this->tipo_consulta_id,
                                                                $this->servicio_id,
                                                                $this->motivo,             
                                                                $this->medico_id,          
                                                                $this->especialidad_id,    
                                                                $this->sitio_id,  
                                                                $this->proveedor_id,     
                                                                $this->estado_atencion_id, 
                                                                $this->is_cita, 
                                                                $this->is_automatica,              
                                                                $this->fecha_cita,         
                                                                $this->fecha_ingreso,      
                                                                $this->fecha_egreso,       
                                                                $this->observaciones,      
                                                                $this->fecha_atencion,     
                                                                $this->diagnostico_id,
                                
                                                                $this->usuario_creador,
                                                                $this->nombre_creador,
                                                                $this->usuario_modificador,
                                                                $this->nombre_modificador,
                                                                $this->turno_id,
                                                                $this->beneficiario_id
                
                                                        ));
                                                }
                                        

                                }
                           }

                           // Si la orden es de laboratorio, se deben registrar los examenes aprobados en la orden

                           if ($this->tipo_atencion_id == 4 ){
                                if ($this->examenes){
                                        foreach($this->examenes as $examen) { 
                                                
                                                $sql = "INSERT INTO atencion_examen (
                                                        atencion_id,
                                                        examen_id
                                                  ) 
                                                  VALUES (?,?)";
                
                                                $stm = $this->pdo->prepare($sql);
                                                $stm->execute(array(
                                                        $id, 
                                                        $examen['examen_id']
                                                ));
                                                
                                        }

                                }               
                           }

                           //Actualizar el estado de la solicitud a la que pertenece la orden de servicio
                           $sql = "
                           UPDATE solicitud SET 
                                   estado_solicitud_id = 4,
                                   usuario_modificador = '$this->usuario_creador',
                                   nombre_modificador  = '$this->nombre_creador',
                                   modified = now()
                           WHERE solicitud_id =   $this->solicitud_id  ";
                       
                       
                           $stm = $this->pdo->prepare($sql);
                           $stm->execute();
   
   
                           $this->response->setResponse(ListaCodigoMensaje::$ACCION_CREAR_ORDENES_TRIAJE, "Se ha(n) creado correctamente las orden(es)..", ListaCodigoMensaje::$COD_CREAR_ORDENES_TRIAJE ); 
                      }else{
                           //No hay cupos
                           $this->response->setResponse(ListaCodigoMensaje::$ACCION_CREAR_ORDENES_TRIAJE, "No se ha(n) podido crear la(s) orden(es). No hay cupos para la fecha seleccionada.", ListaCodigoMensaje::$COD_ERROR );
                         
                      }
   
                      $this->pdo = null;
   
                      return $this->response; 
   
                   }catch(PDOException $e){
                           
                           $this->response->setResponse(ListaCodigoMensaje::$ACCION_CREAR_ORDENES_TRIAJE, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
                           return $this->response;
                   }
   
          }else{
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_CREAR_ORDENES_TRIAJE, 'No existe ID de solicitud para asociar a las órdenes.', ListaCodigoMensaje::$COD_ERROR );
          }  
                
	}
	
	public function Cancelar_orden_consulta($atencion)
	{

		     //Si el servicio es consulta debe cancelar todas las ordenes relacionadas a la solicitud (Porque es Vestida (automatica=1))
		     if ($atencion->servicio_id == 1) 
			 {					
				    //Obtener el cupo relacionado
					
					$um_cupo= new CuposModel(null);
   
                    $cupo = $um_cupo->ObtenerCupo_Citas($atencion);
						
					 /* $sql = "SELECT *
							FROM hcm_db.cupos c
							
							WHERE   c.sitio_id         = $atencion->sitio_id AND 
									c.medico_id        = $atencion->medico_id AND 
									c.especialidad_id  = $atencion->especialidad_id AND 
									c.turno_id         = $atencion->turno_id AND 
									c.servicio_id      = $atencion->servicio_id AND 
									c.fecha            = SUBSTRING('$atencion->fecha_cita',1,10);                          
							";


							$stm = $this->pdo->prepare($sql);
							$stm->execute();

							$cupo =  $stm->fetch();*/
							
							
				   // Descuento de cupos asignados
							if (count($cupo) > 0)
						    {
						 
								/*$sql = " UPDATE cupos set cupos_asignados =  cupos_asignados - 1
											WHERE   cupo_id =  $cupo->cupo_id ";
									
								$stm = $this->pdo->prepare($sql);
								$stm->execute();*/
								$cupo = $um_cupo->DescontarCupo($cupo->cupo_id);

						    }
			 }
							
							
							$asignadas =  $this->Obtener_ordenes_asignadas_solicitud(2);
							$procesadas =  $this->Obtener_ordenes_asignadas_solicitud(3);
							//$procesadas = $stm->fetchColumn();
							
							 if ( count($procesadas) > 0 )
							 { 						
                                 ///Cambiar a estado CERRADA 	
									if (count($asignadas) > 0)
									{
										$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada", ListaCodigoMensaje::$COD_CANCELAR_REG );  										
									}
									else
									{	
									    $this->actualizar_solicitud_estatus(2);

										$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada. Se ha cerrado la solicitud asociada a la orden, de forma automática", ListaCodigoMensaje::$COD_CANCELAR_REG );     
									}                                
							   }
							 else
							 {
								    //Verifico si la solicitud tiene ordenes asignadas, al menos una

								    if ( count($asignadas) > 0)
									{
										$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada", ListaCodigoMensaje::$COD_CANCELAR_REG );  									
									}
									else
									{										 

                                        $this->actualizar_solicitud_estatus(3);
										$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada. Se ha cancelado la solicitud asociada a la orden, de forma automática", ListaCodigoMensaje::$COD_CANCELAR_REG );  

										
									}
																 
							 }
							
				  
				
			  
		 
	}

        public function cancelarOrden(){    
                
            try
            {           
  				$this->actualizar_orden_estatus_causa(4);
                $atencion = $this->getById($this->atencion_id);
				
				
                $this->pdo = parent::conexion();

                if (($atencion->tipo_atencion_id == 2) ||  (($atencion->tipo_atencion_id == 5 && $atencion->is_automatica == 1)))
				{
					$this->Cancelar_orden_consulta($atencion);
				}
                else
				{					
                     if   ($atencion->tipo_atencion_id == 5 && $atencion->is_automatica == 0)
					 {

							$sql = "SELECT *
							FROM hcm_db.cupos c
							
							WHERE   c.sitio_id         = $atencion->sitio_id AND 
									c.medico_id        = $atencion->medico_id AND 
									c.especialidad_id  = $atencion->especialidad_id AND 
									c.turno_id         = $atencion->turno_id AND 
									c.servicio_id      = $atencion->servicio_id AND 
									c.fecha            = SUBSTRING('$atencion->fecha_cita',1,10);                          
							";

							$stm = $this->pdo->prepare($sql);
							$stm->execute();

							$cupo =  $stm->fetch();

                    }
					else
					{
							if (($atencion->tipo_atencion_id == 4)){

								$sql = "SELECT *
								FROM hcm_db.cupos c
								
								WHERE   c.tipo_atencion_id         = $atencion->tipo_atencion_id AND 
										c.proveedor_id        = $atencion->proveedor_id AND 
										c.turno_id         = $atencion->turno_id AND 
										c.servicio_id      = $atencion->servicio_id AND 
										c.fecha            = SUBSTRING('$atencion->fecha_cita',1,10);                          
								";


								$stm = $this->pdo->prepare($sql);
								$stm->execute();

								$cupo =  $stm->fetch();
							 }
							 else
							 {
								 if (($atencion->tipo_atencion_id == 3))
								 {
										$sql = "SELECT *
										FROM hcm_db.cupos c
										WHERE   c.tipo_atencion_id         = $atencion->tipo_atencion_id AND 
										c.proveedor_id        = $atencion->proveedor_id AND 
										c.fecha            = SUBSTRING('$atencion->fecha_cita',1,10) AND
										c.grupo_id= 1;";
										$stm = $this->pdo->prepare($sql);
										$stm->execute();
										$cupo =  $stm->fetch();
								}
								else
								{
										$sql = "SELECT * FROM hcm_db.cupos c
												WHERE   c.tipo_atencion_id         = $atencion->tipo_atencion_id AND 
												c.proveedor_id        = $atencion->proveedor_id AND 
												c.turno_id         = $atencion->turno_id AND 
												c.fecha            = SUBSTRING('$atencion->fecha_cita',1,10); ";

										$stm = $this->pdo->prepare($sql);
										$stm->execute();

										$cupo =  $stm->fetch();
								}
							 }
							
					}
						/*Si hay cupo */
				
						 if (count($cupo) > 0)
						 {
						 
							$sql = " UPDATE cupos set cupos_asignados =  cupos_asignados - 1
										WHERE   cupo_id =  $cupo->cupo_id ";
								
							$stm = $this->pdo->prepare($sql);
							$stm->execute();

						 }

						

						$asignadas =  $this->Obtener_ordenes_asignadas_solicitud(2);
           
                //Si la solicitud tiene ordenes asignadas, al menos una, no se cancela la solicitud
					if	 ( count($asignadas) > 0 )
					{
                         $this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada", ListaCodigoMensaje::$COD_CANCELAR_REG );  
                    }
					else
					{

							    //Si la solicitud tiene ordenes procesadas, al menos una, al cancelar la última, 
							    //la solicitud debe quedar en estado cerrada
							    $procesadas =   $this->Obtener_ordenes_asignadas_solicitud(3);
								if	 ( count($procesadas) > 0)
								{
							        $this->actualizar_solicitud_estatus(2);
									$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada. Se ha cerrado la solicitud asociada a la orden, de forma automática", ListaCodigoMensaje::$COD_CANCELAR_REG );     
							    }
								else
								{
									//Cancelo la solicitud
									//Si la solicitud no tiene ordenes asignadas ni procesadas, 
									//se procede a cancelar la solicitud asociada a esa orden
									$this->actualizar_solicitud_estatus(3);
									$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Orden Cancelada. Se ha cancelado la solicitud asociada a la orden, de forma automática", ListaCodigoMensaje::$COD_CANCELAR_REG );  
							   }


                   }
			 }

                $this->pdo = null;
                //$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Se ha(n) creado correctamente las orden(es)..", ListaCodigoMensaje::$COD_CANCELAR_REG ); 
                return $this->response;
                
            }catch(PDOException $e){
                        
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
                return $this->response;
            }
        }

        public function procesarOrden()
		{
           try
           {
                //coloco la orden como procesada
				$this->actualizar_orden_estatus(3);
    
                //Verifico la solicitud
                //1. Si la solicitud tiene ordenes asignadas, al menos una
				$registros1 = $this->Obtener_ordenes_asignadas_solicitud(2);

                if (count($registros1) > 0)
				{
                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Orden Procesada", ListaCodigoMensaje::$COD_MODIFICAR_REG );  
                }
				else
				{
                //Coloco la solicitud cerrada ya que todas sus ordenes estan procesadas
                        $this->actualizar_solicitud_estatus(2);
                        $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Orden Procesada. Se ha cerrado la solicitud asociada a la orden, de forma automática", ListaCodigoMensaje::$COD_MODIFICAR_REG );  
                }
            
                //$this->response->setResponse(ListaCodigoMensaje::$ACCION_CANCELAR_REG, "Se ha(n) creado correctamente las orden(es)..", ListaCodigoMensaje::$COD_CANCELAR_REG ); 
                return $this->response;
                    
            }catch(PDOException $e)
			{
                            
                $this->response->setResponse(ListaCodigoMensaje::$ACCION_CREAR_ORDENES_TRIAJE, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
                return $this->response;
            }
        }
		
		
		public function Obtener_ordenes_asignadas_solicitud($estatus)
		{
			$this->pdo = parent::conexion();
			
			//Verifico la solicitud
                //1. Si la solicitud tiene ordenes asignadas, al menos una
    
                $sql = "SELECT s.solicitud_id, a.atencion_id
                        FROM hcm_db.solicitud s
                        inner join atencion a on 
                        (s.solicitud_id = a.solicitud_id and a.estado_atencion_id in ($estatus))
                        where  s.solicitud_id = $this->solicitud_id  ";
            
                $stm = $this->pdo->prepare($sql);
                $stm->execute();
    
                $registros =  $stm->fetchAll();
			
			    $this->pdo = null;
				return $registros;
		}
		
		public function actualizar_orden_estatus($estatus)
		{
			$this->pdo = parent::conexion();
			
			  $sql = "
                UPDATE atencion SET 
                        estado_atencion_id = $estatus,
                        usuario_modificador = '$this->usuario_modificador',
                        nombre_modificador  = '$this->nombre_modificador',
                        modified = now()
                WHERE   atencion_id =   $this->atencion_id  ";
                
                $stm = $this->pdo->prepare($sql);
                $stm->execute();
   
			
			    $this->pdo = null;
			
		}
		
		public function actualizar_orden_estatus_causa($estatus)
		{
			$this->pdo = parent::conexion();
			
			  $sql = "
                UPDATE atencion SET 
                        estado_atencion_id = $estatus,
						causa_id = $this->causa_id,
                        usuario_modificador = '$this->usuario_modificador',
                        nombre_modificador  = '$this->nombre_modificador',
                        modified = now()
                WHERE   atencion_id =   $this->atencion_id  ";
                
                $stm = $this->pdo->prepare($sql);
                $stm->execute();
   
			
			    $this->pdo = null;
			
		}



		///Actualiza el estatus de la solicitud de una orden 
		public function actualizar_solicitud_estatus($estatus)
		{
			$this->pdo = parent::conexion();
			 $sql = "
                        UPDATE solicitud SET 
                                estado_solicitud_id = $estatus,
                                usuario_modificador = '$this->usuario_modificador',
                                nombre_modificador  = '$this->nombre_modificador',
                                modified = now()
                        WHERE   solicitud_id =   $this->solicitud_id  ";
                
                        $stm = $this->pdo->prepare($sql);
                        $stm->execute();
			
			
			$this->pdo = null;
		}

        public function getOrdenesBySolicitud()
		{
           try
           {
                $this->pdo = parent::conexion();
		
                
                $sql=""; 

                $sql="SELECT  a.*, 
                                ta.nombre as nombre_tipo_atencion, 
                                pr.nombre as nombre_prioridad,
                                s.nombre as nombre_servicio,
                                concat(m.nombres, ' ' , m.apellidos) as nombre_medico,
                                e.nombre as nombre_especialidad,
                                si.nombre as nombre_sitio,
                                p.nombre as nombre_proveedor,
                                ea.nombre as nombre_estado_atencion,
                                tc.nombre as nombre_tipo_consulta,
                                DATE (a.fecha_cita)  as fecha_cita_formato,
                                cau.motivo as nombre_causa
                        FROM hcm_db.atencion a
                        left JOIN tipo_atencion ta    on a.tipo_atencion_id = ta.tipo_atencion_id
                        left JOIN prioridad pr         on a.prioridad_id = pr.prioridad_id
                        left join servicio s 	        on a.servicio_id = s.servicio_id
                        left join medico m 		on a.medico_id = m.medico_id
                        left join especialidad e      on a.especialidad_id = e.especialidad_id
                        left join sitio si 		on a.sitio_id = si.sitio_id
                        left join proveedor p 	on a.proveedor_id = p.proveedor_id
                        left join estado_atencion ea  on ea.estado_atencion_id = a.estado_atencion_id
                        left join diagnostico d       on a.diagnostico_id = d.diagnostico_id  
                        left join tipo_consulta tc    on a.tipo_consulta_id = tc.tipo_consulta_id  
                        left join causa cau    on a.causa_id = cau.causa_id 
                        
                        WHERE a.solicitud_id = $this->solicitud_id
                ";  

                //echo $sql;

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

public function getOrdenesBySolicitudEstatus(){
    try
    {
            $this->pdo = parent::conexion();

            $sql=""; 

            $sql="SELECT  a.atencion_id,a.solicitud_id,
			          date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
					  a.motivo, date_format(a.fecha_cita, '%d/%m/%Y') AS fecha_cita,
                          ta.nombre as nombre_tipo_atencion, 
                          pr.nombre as prioridad_id,
                          s.nombre as nombre_servicio,
                          concat(m.nombres, m.apellidos) as nombre_medico,
                          e.nombre as nombre_especialidad,
                          si.nombre as nombre_sitio,
                          p.nombre as nombre_proveedor,
                          ea.nombre as nombre_estado_atencion,
                          tc.nombre as nombre_tipo_consulta,
                          cau.nombre as nombre_causa,
						  a.is_automatica,
						  a.tipo_atencion_id
                  FROM hcm_db.atencion a
                  left JOIN tipo_atencion ta    on a.tipo_atencion_id = ta.tipo_atencion_id
                  left JOIN prioridad pr        on a.prioridad_id = pr.prioridad_id
                  left join servicio s 	        on a.servicio_id = s.servicio_id
                  left join medico m 		on a.medico_id = m.medico_id
                  left join especialidad e      on a.especialidad_id = e.especialidad_id
                  left join sitio si 		on a.sitio_id = si.sitio_id
                  left join proveedor p 	on a.proveedor_id = p.proveedor_id
                  left join estado_atencion ea  on ea.estado_atencion_id = a.estado_atencion_id
                  left join diagnostico d       on a.diagnostico_id = d.diagnostico_id  
                  left join tipo_consulta tc    on a.tipo_consulta_id = tc.tipo_consulta_id  
                  left join causa cau           on a.causa_id  = cau.causa_id 
                  
                  WHERE a.solicitud_id = $this->solicitud_id and  not a.estado_atencion_id in (4,5)  ";  

            //echo $sql;

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


public function getOrdenesByOrden(){
    try
    {
            $this->pdo = parent::conexion();

            $sql=""; 

            $sql="SELECT  a.*, 
                          ta.nombre as nombre_tipo_atencion, 
                          pr.nombre as prioridad_id,
                          s.nombre as nombre_servicio,
                          concat(m.nombres, m.apellidos) as nombre_medico,
                          e.nombre as nombre_especialidad,
                          si.nombre as nombre_sitio,
                          p.nombre as nombre_proveedor,
                          ea.nombre as nombre_estado_atencion,
                          tc.nombre as nombre_tipo_consulta
                  FROM hcm_db.atencion a
                  left JOIN tipo_atencion ta    on a.tipo_atencion_id = ta.tipo_atencion_id
                  left JOIN prioridad pr         on a.prioridad_id = pr.prioridad_id
                  left join servicio s 	        on a.servicio_id = s.servicio_id
                  left join medico m 		on a.medico_id = m.medico_id
                  left join especialidad e      on a.especialidad_id = e.especialidad_id
                  left join sitio si 		on a.sitio_id = si.sitio_id
                  left join proveedor p 	on a.proveedor_id = p.proveedor_id
                  left join estado_atencion ea  on ea.estado_atencion_id = a.estado_atencion_id
                  left join diagnostico d       on a.diagnostico_id = d.diagnostico_id  
                  left join tipo_consulta tc    on a.tipo_consulta_id = tc.tipo_consulta_id  
                  
                  WHERE a.atencion_id = $this->atencion_id and  not a.estado_atencion_id in (4,5) 
            ";  

            //echo $sql;

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

	
}
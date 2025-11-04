<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use App\Lib\Response;
use App\Lib\Connection;
use PDO;
use PDOException;


class SolicitudElecReportModel extends Connection
{
	
	 public $response;
	 
	  public function __construct()
	  {
        $this->response = new Response();
      }
	  
	  ///
	  
	  //Reporte Solicitud By tipo de atencion 

public function Reporte_Solicitud_By_Atencion($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT distinct a.beneficiario_id,a.solicitud_id,
			          date_format(a.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					 b.nombres as nombre_beneficiario,
					 b.apellidos as nombre_titular,
			          ta.nombre as tipo_solicitud_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre,
                      a.telefono1 as telefono,a.telefono2 as telefono2,
							  CASE es.estado_solicitud_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,
                      a.observacion,
					  tas.tipo_atencion_id as tipo_atencion_id,
                      tas.nombre as nombre_tipo_atencion,
                      en.nombre as ENTE_RESPONSABLE 					  
                      FROM solicitud a  
			          INNER JOIN tipo_atencion tas on tas.tipo_atencion_id = a.tipo_atencion_id and tas.tipo_solicitud_id = a.tipo_solicitud_id
                      INNER JOIN tipo_solicitud ta on ta.tipo_solicitud_id = a.tipo_solicitud_id
			          INNER JOIN estado_solicitud es on es.estado_solicitud_id =a.estado_solicitud_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
					  INNER JOIN beneficiario b on b.beneficiario_id  = a.beneficiario_id
					  LEFT JOIN ente en on en.ente_id = a.ente_id 
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where a.solicitud_id= ? and   a.tipo_solicitud_id = 3 ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and a.tipo_atencion_id = ?";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
                    $sql.=" where  
				       (( a.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					   AND ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(a.fecha_solicitud) between  ? and  ? and   a.tipo_solicitud_id = 3 )
				       
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ( ( ((a.estado_solicitud_id = ?  or '".$data['estado_solicitud_id']."' IS NULL OR '".$data['estado_solicitud_id']. "'=' '))) )
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					     AND ((a.ente_id = ?  or '".$data['ente_id']."' IS NULL OR '".$data['ente_id']. "'=' ')) 
					   ";
				}
            }
      
           $sql.=" ORDER BY a.solicitud_id desc ";
		  // echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_id']));
               }
		       else
			   {
			      $stm->execute( array(
				  $data['tipo_atencion_id'],
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['especialidad_id'],
				  $data['estado_solicitud_id'],
				  $data['usuario_creador'],
				  $data['ente_id']));
			  }
           }
            
          
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
	  
	  ///

	  	  
	  //Reporte Solicitud By tipo de atencion 

public function Rep_Solic_Triaje_By_Atencion($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT distinct a.beneficiario_id,a.solicitud_id,
			          date_format(a.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,
			          ta.nombre as tipo_solicitud_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre,
                      a.telefono1 as telefono,a.telefono2 as telefono2,
							  CASE es.estado_solicitud_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,
                      a.observacion,
					  tas.tipo_atencion_id as tipo_atencion_id,
                      tas.nombre as nombre_tipo_atencion
                      FROM solicitud a  
			          INNER JOIN tipo_atencion tas on tas.tipo_atencion_id = a.tipo_atencion_id and tas.tipo_solicitud_id = a.tipo_solicitud_id
                      INNER JOIN tipo_solicitud ta on ta.tipo_solicitud_id = a.tipo_solicitud_id
			          INNER JOIN estado_solicitud es on es.estado_solicitud_id =a.estado_solicitud_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
					  INNER JOIN beneficiario b on b.beneficiario_id  = a.beneficiario_id 

					  
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where a.solicitud_id= ?  and a.tipo_atencion_id = ?  and a.estado_solicitud_id = 1 ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and a.tipo_atencion_id = ?";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
                    $sql.=" where  
				       ((a.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					   AND ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(a.fecha_solicitud) between  ? and  ? )
				       AND ((a.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_solicitud_id = 1 ))
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   
					   ";
				}
            }
      
           $sql.=" ORDER BY a.solicitud_id desc ";
		  // echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id'],$data['tipo_atencion_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_id']));
               }
		       else
			   {
			      $stm->execute( array(
				  $data['tipo_atencion_id'],
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['tipo_solicitud_id'],
			      $data['especialidad_id'],
				  $data['usuario_creador']));
			  }
           }
            
          
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
	  
	  /// Triaje
	  
	  	  //Reporte Solicitud By tipo de atencion 

public function Rep_Solic_TriajeLab_By_Atencion($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT distinct  a.beneficiario_id,a.solicitud_id,
						date_format(a.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
						b.cedula_titular,
						b.cedula_hcm as cedula_beneficiario,
					 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0  ) as nombre_titular,
						ta.nombre as tipo_solicitud_nombre,
						e.nombre as especialidad_nombre,
						es.nombre as estado_nombre,
						a.telefono1 as telefono,a.telefono2 as telefono2,
						CASE es.estado_solicitud_id 
						  when 2 then false
						 else
							 true end as estado,
						a.usuario_creador,a.nombre_creador,
						a.observacion,
						tas.tipo_atencion_id as tipo_atencion_id,
						tas.nombre as nombre_tipo_atencion	
						FROM solicitud a  
						INNER JOIN tipo_atencion tas on tas.tipo_atencion_id = a.tipo_atencion_id and tas.tipo_solicitud_id = a.tipo_solicitud_id
						INNER JOIN tipo_solicitud ta on ta.tipo_solicitud_id = a.tipo_solicitud_id
						INNER JOIN estado_solicitud es on es.estado_solicitud_id =a.estado_solicitud_id
						INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
						INNER JOIN beneficiario b on b.beneficiario_id  = a.beneficiario_id ";
						
						/*
						inner join examen ex on ss.examen_id=ex.examen_id 
						inner join grupo_examen g on g.examen_id = ex.examen_id
						INNER join grupo gr on  gr.grupo_id = g.grupo_id 
					  
					  ";*/
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where a.solicitud_id= ?  and a.tipo_atencion_id = ?  and a.estado_solicitud_id = 1 ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and a.tipo_atencion_id = ?";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
                    $sql.=" where  
				       ((a.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					   AND ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(a.fecha_solicitud) between  ? and  ? )
				       AND ((a.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_solicitud_id = 1 ))
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))

					   ";
				}
            }
      
           $sql.=" ORDER BY a.solicitud_id desc ";
		  // echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id'],$data['tipo_atencion_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_id']));
               }
		       else
			   {
			      $stm->execute( array(
				  $data['tipo_atencion_id'],
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['tipo_solicitud_id'],
			      $data['especialidad_id'],
				  $data['usuario_creador']));
			  }
           }
            
          
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
	  
	  ///

	  


	  ////
	  
	  public function Reporte_Orden_By_Atencion($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT distinct
			          date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
					  a.motivo,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,
					  s.solicitud_id,
					  a.atencion_id,
					  s.tipo_atencion_id as tipo_atencion_solicitud_id,
					  ta.nombre as tipo_atencion_solicitud,
					  a.tipo_atencion_id as tipo_atencion_id,	
			          ta.nombre as tipo_atencion_nombre,
					  se.nombre as servicio_nombre,
			          CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre, 
					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,s.telefono2 as telefono2,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,a.is_automatica,a.tipo_atencion_id,
                      p.nombre as nombre_prioridad, 
                      tc.nombre as nombre_tipo_consulta,
					  CASE 
					      s.tipo_atencion_id
							WHEN 5 THEN 'PROCEDIMIENTO' 
							WHEN 2 THEN 'CONSULTA / VESTIDA' 
							ELSE ''  
					  END as tipo_proceso
                    
							 
			          FROM atencion a   
                   INNER JOIN solicitud s on s.solicitud_id=a.solicitud_id and s.tipo_atencion_id = a.tipo_atencion_id
                   INNER JOIN tipo_atencion ta on ta.tipo_atencion_id=a.tipo_atencion_id
					    INNER JOIN servicio se on se.servicio_id = a.servicio_id
                   INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id 
					    INNER JOIN prioridad p on p.prioridad_id = a.prioridad_id 
                   INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = a.tipo_consulta_id
						 INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id 
 
					 
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where a.solicitud_id= ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? ";
				}
                else
				{
 
				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(a.fecha_atencion) between  ? and  ? )
				       AND ((a.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((a.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((a.estado_atencion_id <> 4 ))
					   AND ((a.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((a.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }

			// AND ((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
			
           $sql.=" ORDER BY a.atencion_id desc ";
		  //echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
				  $data['tipo_atencion_solicitud_id'],
			      $data['especialidad_id'],
				  $data['medico_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador']));
			  }
           }
            
		   //$data['tipo_atencion_id'],

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
	  
	  ///
	
	public function Reporte_por_Solicitud_Servicio($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = " select 
             distinct 	date_format(s.created, '%d/%m/%Y') AS FECHA_REGISTRO ,
			 mes(s.created, 'es_ES') as MES_REGISTRO,
			 YEAR(s.created) as ANIO_REGISTRO,
			 s.solicitud_id as CODIGO_REGISTRO,	
			 date_format(s.fecha_solicitud, '%d/%m/%Y') AS FECHA_RECEPCION  ,
			 mes(s.fecha_solicitud, 'es_ES') as MES_RECIBIDO,
			 YEAR(s.fecha_solicitud) as ANIO_RECIBIDO,
			 b.cedula_titular as CEDULA_TITULAR,
             (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as NOMBRE_TITULAR,			 
			 b.cedula_hcm as CEDULA_PACIENTE,
			 b.nombres as NOMBRE_PACIENTE,
			 b.parentesco AS PARENTESCO,
			 TIMESTAMPDIFF(YEAR, b.fecha_nacimiento, CURDATE()) AS EDAD ,
			 s.telefono1 as TELEFONO,s.telefono2 as TELEFONO2,
			 t.descripcion as TIPO_SOLICITUD,
			 ta.nombre as TIPO_DE_SOLICITUD,
			 td.nombre as CLASE_DE_DIAGNOSTICO,
			 dg.nombre as DIAGNOSTICO_GENERAL,
			 s.motivo as DIAGNOSTICO_ESPECIFICO,
			 p1.nombre_grupo AS PROCEDIMIENTO_GENERAL,
			 p1.nombre_servicio AS PROCEDIMIENTO_ESPECIFICO,
			 CONCAT(m.nombres,' ',m.apellidos)  as MEDICO_TRATANTE,
			 e.nombre as ESPECIALIDAD,
			 pr.nombre as PRIORIDAD,
			 dom.nombre as UBICACION_GEOGRAFICA,
			 p1.nombre AS PROVEEDOR1,
             p1.precio_dolar AS DOLARES_1,
             p1.precio_bs AS BOLIVARES_1,
			 p1.precio_tasa AS TIPO_DE_CAMBIO_1,
			 p2.nombre AS PROVEEDOR_2,
             p2.precio_dolar AS DOLARES_2,
             p2.precio_bs AS BOLIVARES_2,
			  p2.precio_tasa AS TIPO_DE_CAMBIO_2,
			 en.nombre as  ENTE_RESPONSABLE,
			 es.descripcion as ESTATUS_DE_SOLICITUD,
			 c.motivo as CAUSA_ESTADO_SOLICITUD,
			 OBSERVACION,
			 s.usuario_creador as USUARIO_CREADOR,
			 s.nombre_creador as NOMBRE_CREADOR 


         FROM
           solicitud s
         LEFT JOIN (
				    SELECT
				        sol.solicitud_id,
				        p.proveedor_id,
				        p.nombre,
				        sp.precio_dolar,
				        sp.precio_bs,
						sp.precio_tasa,
				        ser.nombre as nombre_servicio,
				        gr.nombre as nombre_grupo,
				        ROW_NUMBER() OVER (PARTITION BY sp.solicitud_id ORDER BY sp.solicitud_presupuesto_id) AS rn
				    FROM
		                        solicitud sol 
		                    LEFT JOIN solicitud_presupuesto sp ON sp.solicitud_id = sol.solicitud_id
		                    LEFT JOIN tipo_grupo tg on tg.tipo_grupo_id = sol.tipo_atencion_id 
		                    LEFT JOIN  grupo gr on  gr.tipo_grupo_id =tg.tipo_grupo_id
		                    INNER JOIN  grupo_servicio gs ON sp.servicio_id = gs.servicio_id and gr.grupo_id=gs.grupo_id 
		                    INNER JOIN  servicio ser ON sp.servicio_id = ser.servicio_id 
			 INNER JOIN  proveedor p ON sp.proveedor_id = p.proveedor_id
       ) p1 ON s.solicitud_id = p1.solicitud_id AND p1.rn = 1
       LEFT JOIN (
				    SELECT
				        sol.solicitud_id,
				        p.proveedor_id,
				        p.nombre,
				        sp.precio_dolar,
				        sp.precio_bs,
						  sp.precio_tasa,
				        ser.nombre as nombre_servicio,
				        gr.nombre as nombre_grupo,
						  ROW_NUMBER() OVER (PARTITION BY sp.solicitud_id ORDER BY sp.solicitud_presupuesto_id) AS rn
				    FROM solicitud sol
				        		   
		                    LEFT JOIN   solicitud_presupuesto sp ON sp.solicitud_id = sol.solicitud_id
		                    LEFT JOIN tipo_grupo tg on tg.tipo_grupo_id = sol.tipo_atencion_id 
		                    LEFT JOIN  grupo gr on  gr.tipo_grupo_id =tg.tipo_grupo_id
		                    INNER JOIN  grupo_servicio gs ON sp.servicio_id = gs.servicio_id and gr.grupo_id=gs.grupo_id 
		                    INNER JOIN  servicio ser ON sp.servicio_id = ser.servicio_id 
			            INNER JOIN  proveedor p ON sp.proveedor_id = p.proveedor_id
       ) p2 ON s.solicitud_id = p2.solicitud_id AND p2.rn = 2 
       
          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
		  INNER JOIN especialidad e on e.especialidad_id = s.especialidad_id 
		  INNER JOIN estado_solicitud es on es.estado_solicitud_id =s.estado_solicitud_id
          INNER JOIN tipo_solicitud t on t.tipo_solicitud_id = s.tipo_solicitud_id 
		  INNER JOIN tipo_atencion ta on ta.tipo_atencion_id = s.tipo_atencion_id 
		  LEFT  JOIN causa_solicitud c on c.causa_solicitud_id = s.causa_solicitud_id
		  LEFT JOIN domicilio dom on dom.domicilio_id = s.domicilio_id
	      LEFT JOIN ente en on  en.ente_id = s.ente_id 
	      LEFT JOIN prioridad pr on  pr.prioridad_id = s.prioridad_id 
		  INNER JOIN medico m on m.medico_id = s.medico_id
		  LEFT JOIN  diagnostico dg on s.diagnostico_id = dg.diagnostico_id
		  LEFT JOIN tipo_diagnostico td on td.tipo_diagnostico_id = dg.tipo_diagnostico_id 	 
					 ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				$cedula =$data['cedula_beneficiario'];
				$fecha_inicio = $data['fecha_inicio'];
				$fecha_fin = $data['fecha_fin'];
				$tipo_sol =  $data['tipo_solicitud_id'];
				$origen = $data['tipo_cobertura_id'];
                $sql.=" where  
				((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                  AND DATE(s.fecha_solicitud) between  ? and  ? )
				  AND ((t.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' '))
                  AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' '))				  
				  AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				  AND ((es.estado_solicitud_id = ?  or '".$data['estado_solicitud_id']."' IS NULL OR '".$data['estado_solicitud_id']. "'=' '))
                  AND ((s.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
				  AND ((s.domicilio_id = ?  or '".$data['domicilio_id']."' IS NULL OR '".$data['domicilio_id']. "'=' '))
				  AND ((s.ente_id = ?  or '".$data['ente_id']."' IS NULL OR '".$data['ente_id']. "'=' '))
				  AND ((s.prioridad_id = ?  or '".$data['prioridad_id']."' IS NULL OR '".$data['prioridad_id']. "'=' '))
				  ";
            }


           $sql.=" ORDER BY s.solicitud_id desc";

		   
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
               $stm->execute( array( 
               $data['cedula_beneficiario'],
			   $data['fecha_inicio'] ,
			   $data['fecha_fin'],
			   $data['tipo_solicitud_id'],
			   $data['tipo_atencion_id'],
			   $data['especialidad_id'],
			   $data['estado_solicitud_id'],
			   $data['usuario_creador'],
			   $data['domicilio_id'],
			   $data['ente_id'],
			   $data['prioridad_id']
             ));
           }
            
          
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
	
	public function Reporte_por_Solicitud_Abiertas($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = " SELECT distinct mes(s.fecha_solicitud, 'es_ES') AS mes_solicitud, date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
					 s.solicitud_id,
					 t.descripcion AS tipo_solicitud,
					 es.descripcion AS estado_solicitud,
					 ta.nombre AS nombre_tipo_atencion,
					 e.nombre AS especialidad,
					 serv.nombre AS servicio_nom,
					  CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
			         b.cedula_titular,
					 b.cedula_hcm AS cedula_beneficiario,
	                 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,
					 s.telefono1 AS telefono,s.telefono2 AS telefono2,
					 observacion,
					 s.usuario_creador,s.nombre_creador, dom.nombre as nombre_domicilio
					 FROM solicitud s 
					 INNER JOIN solicitud_servicio ss ON ss.solicitud_id = s.solicitud_id
					 INNER JOIN servicio serv ON ss.servicio_id = serv.servicio_id				 
			         INNER JOIN beneficiario b ON b.beneficiario_id = s.beneficiario_id
			         INNER JOIN especialidad e ON e.especialidad_id = s.especialidad_id 
			         INNER JOIN estado_solicitud es ON es.estado_solicitud_id =s.estado_solicitud_id
                     INNER JOIN tipo_solicitud t ON t.tipo_solicitud_id = s.tipo_solicitud_id 
					 INNER JOIN tipo_atencion ta ON ta.tipo_atencion_id = s.tipo_atencion_id 
					  INNER JOIN medico m on m.medico_id = s.medico_id
					  LEFT JOIN domicilio dom on dom.domicilio_id = s.domicilio_id
					 
					 ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				$cedula =$data['cedula_beneficiario'];
				$fecha_inicio = $data['fecha_inicio'];
				$fecha_fin = $data['fecha_fin'];
				$tipo_sol =  $data['tipo_solicitud_id'];
				$origen = $data['tipo_cobertura_id'];
				$requerimiento = $data['tipo_atencion_id'];
                $sql.=" where  
				((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                  AND DATE(s.fecha_solicitud) between  ? and  ? )
				  AND ((s.tipo_cobertura_id = ?  or '".$data['tipo_cobertura_id']."' IS NULL OR '".$data['tipo_cobertura_id']. "'=' ')) 
				  AND ((t.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' ')) 
				  AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				  AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
				  AND ((es.estado_solicitud_id = 1  ))
                   AND ((s.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
				   AND ((s.domicilio_id = ?  or '".$data['domicilio_id']."' IS NULL OR '".$data['domicilio_id']. "'=' '))			 				  ";
            }


           $sql.=" ORDER BY solicitud_id desc";

		   
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
               $stm->execute( array( 
               $data['cedula_beneficiario'],
			   $data['fecha_inicio'] ,
			   $data['fecha_fin'],
			   $data['tipo_cobertura_id'],
			   $data['tipo_solicitud_id'],
			   $data['especialidad_id'],
			   $data['tipo_atencion_id'],
			   $data['usuario_creador'],
			   $data['domicilio_id']
             ));
           }
            
          
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
	
	public function Reporte_por_Solicitud_Abiertas_Imagen($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = " SELECT distinct mes(s.fecha_solicitud, 'es_ES') AS mes_solicitud,date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
					 s.solicitud_id,
					 t.descripcion AS tipo_solicitud,
					 es.descripcion AS estado_solicitud,
					 ta.nombre AS nombre_tipo_atencion,
					 e.nombre AS especialidad,
					 serv.nombre AS servicio_nom,
			         b.cedula_titular,
					 b.cedula_hcm AS cedula_beneficiario,
						 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,

					 s.telefono1 AS telefono,s.telefono2 AS telefono2,
					 observacion,
					 s.usuario_creador,s.nombre_creador	,
                      serv.nombre as servicio_nom	,
					  gr.nombre as nombre_grupo, dom.nombre as nombre_domicilio
					 FROM solicitud s 
					 INNER JOIN solicitud_servicio ss ON ss.solicitud_id = s.solicitud_id
					 INNER join grupo_servicio g on g.servicio_id = ss.servicio_id
					 INNER join grupo gr on  gr.grupo_id = g.grupo_id 
					 INNER JOIN servicio serv ON ss.servicio_id = serv.servicio_id				 
			         INNER JOIN beneficiario b ON b.beneficiario_id = s.beneficiario_id
			         INNER JOIN especialidad e ON e.especialidad_id = s.especialidad_id 
			         INNER JOIN estado_solicitud es ON es.estado_solicitud_id =s.estado_solicitud_id
                     INNER JOIN tipo_solicitud t ON t.tipo_solicitud_id = s.tipo_solicitud_id 
					 INNER JOIN tipo_atencion ta ON ta.tipo_atencion_id = s.tipo_atencion_id
					 LEFT JOIN domicilio dom on dom.domicilio_id = s.domicilio_id
					 
					 ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				$cedula =$data['cedula_beneficiario'];
				$fecha_inicio = $data['fecha_inicio'];
				$fecha_fin = $data['fecha_fin'];
				$tipo_sol =  $data['tipo_solicitud_id'];
				$origen = $data['tipo_cobertura_id'];
				$requerimiento = $data['tipo_atencion_id'];
                $sql.=" where  
				((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                  AND DATE(s.fecha_solicitud) between  ? and  ? )
				  AND ((s.tipo_cobertura_id = ?  or '".$data['tipo_cobertura_id']."' IS NULL OR '".$data['tipo_cobertura_id']. "'=' ')) 
				  AND ((t.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' ')) 
				  AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				  AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
				  AND ((es.estado_solicitud_id = 1  ))
                  AND ((s.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
				  AND ((gr.grupo_id = ?  or '".$data['grupo_id']."' IS NULL OR '".$data['grupo_id']. "'=' '))
				  AND ((ss.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))  
				  AND ((s.domicilio_id = ?  or '".$data['domicilio_id']."' IS NULL OR '".$data['domicilio_id']. "'=' ')) ";
            }


           $sql.=" ORDER BY solicitud_id desc";

		   
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
               $stm->execute( array( 
               $data['cedula_beneficiario'],
			   $data['fecha_inicio'] ,
			   $data['fecha_fin'],
			   $data['tipo_cobertura_id'],
			   $data['tipo_solicitud_id'],
			   $data['especialidad_id'],
			   $data['tipo_atencion_id'],
			   $data['usuario_creador'],
			   $data['grupo_id'],
			   $data['servicio_id'],
			   $data['domicilio_id']
			   )
			   );
           }
            
          
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
	
	public function Reporte_por_Solicitud_Abiertas_Lab($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = " SELECT distinct mes(s.fecha_solicitud, 'es_ES') AS mes_solicitud,date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
                                                                                s.solicitud_id,
                                                                                t.descripcion AS tipo_solicitud,
                                                                                es.descripcion AS estado_solicitud,
                                                                                ta.nombre AS nombre_tipo_atencion,
                                                                                e.nombre AS especialidad,
                                                                                serv.nombre AS servicio_nom,
                                                                                g.nombre AS nombre_grupo,
                     ex.nombre AS nombre_examen,
                                                         b.cedula_titular,
                                                                                b.cedula_hcm AS cedula_beneficiario,
                                                                                	 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,

                                                                                s.telefono1 AS telefono,s.telefono2 AS telefono2,
                                                                                observacion,
                                                                                s.usuario_creador,s.nombre_creador, dom.nombre as nombre_domicilio
                                                                                FROM solicitud s 
                                                                                 INNER JOIN solicitud_servicio ss ON ss.solicitud_id = s.solicitud_id
                                                                                INNER JOIN servicio serv ON ss.servicio_id = serv.servicio_id                                                     
                                                         INNER JOIN beneficiario b ON b.beneficiario_id = s.beneficiario_id
                                                         INNER JOIN especialidad e ON e.especialidad_id = s.especialidad_id 
                                                         INNER JOIN estado_solicitud es ON es.estado_solicitud_id =s.estado_solicitud_id
														 INNER JOIN tipo_solicitud t ON t.tipo_solicitud_id = s.tipo_solicitud_id 
														 INNER JOIN tipo_atencion ta ON ta.tipo_atencion_id = s.tipo_atencion_id 
														 INNER JOIN solicitud_examen sex ON s.solicitud_id = sex.solicitud_id
														 INNER JOIN examen ex ON sex.examen_id= ex.examen_id  
														 INNER JOIN grupo_examen gex on gex.examen_id =  ex.examen_id  
														  inner join grupo g on g.grupo_id =  gex.grupo_id 
														  LEFT JOIN domicilio dom on dom.domicilio_id = s.domicilio_id					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				$cedula =$data['cedula_beneficiario'];
				$fecha_inicio = $data['fecha_inicio'];
				$fecha_fin = $data['fecha_fin'];
				$tipo_sol =  $data['tipo_solicitud_id'];
				$origen = $data['tipo_cobertura_id'];
				$requerimiento = $data['tipo_atencion_id'];
                $sql.=" where  
				((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                  AND DATE(s.fecha_solicitud) between  ? and  ? )
				  AND ((s.tipo_cobertura_id = ?  or '".$data['tipo_cobertura_id']."' IS NULL OR '".$data['tipo_cobertura_id']. "'=' ')) 
				  AND ((t.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' ')) 
				  AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				  AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
				  AND ((es.estado_solicitud_id = 1  ))
                   AND ((s.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
				   AND ((gex.grupo_id = ?  or '".$data['grupo_id']."' IS NULL OR '".$data['grupo_id']. "'=' '))
				AND ((sex.examen_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
				  AND ((s.domicilio_id = ?  or '".$data['domicilio_id']."' IS NULL OR '".$data['domicilio_id']. "'=' ')) 				  ";
            }


           $sql.=" ORDER BY solicitud_id desc";

		   
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
               $stm->execute( array( 
               $data['cedula_beneficiario'],
			   $data['fecha_inicio'] ,
			   $data['fecha_fin'],
			   $data['tipo_cobertura_id'],
			   $data['tipo_solicitud_id'],
			   $data['especialidad_id'],
			   $data['tipo_atencion_id'],
			   $data['usuario_creador'],
			   $data['grupo_id'],
			   $data['servicio_id'],
			   $data['domicilio_id']
			   ));
           }
            
          
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

	
public function Reporte_por_Orden($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT distinct
					    mes(s.fecha_solicitud, 'es_ES') AS mes_solicitud,
					    mes(a.fecha_atencion, 'es_ES') AS mes_atencion,			
                  	  date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
					  s.solicitud_id,			  
			          a.atencion_id,
					  date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					 	 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,

			          e.nombre as especialidad_nombre,
					  CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  se.nombre as servicio_nombre,					  
					  es.nombre as estado_nombre,
					  cau.motivo as nombre_causa,
					  a.motivo, 
					  s.tipo_atencion_id as tipo_atencion_solicitud_id,	
					  ta1.nombre as tipo_atencion_solicitud,					  
					  ta.nombre as tipo_atencion_orden,
  					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,s.telefono2 as telefono2,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,
                      p.nombre as nombre_prioridad,
                      tc.nombre as nombre_tipo_consulta,
					  CASE 
					      s.tipo_atencion_id
							WHEN 5 THEN 'PROCEDIMIENTO' 
							WHEN 2 THEN 'CONSULTA / VESTIDA' 
							ELSE ''  
					  END as tipo_proceso
					   							 
			          FROM solicitud s  
			          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id
                      INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
					  INNER JOIN tipo_atencion ta1 on s.tipo_atencion_id=ta1.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id
                      INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id 
					  INNER JOIN prioridad p on p.prioridad_id = a.prioridad_id 
                      INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = a.tipo_consulta_id 
                      LEFT JOIN causa cau    on a.causa_id = cau.causa_id 
					  
					 
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? and s.tipo_atencion_id = ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and s.tipo_atencion_id = ? ";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND ( DATE(s.fecha_solicitud) between  ? and  ? OR  DATE(a.fecha_atencion) between  ? and  ?) )
				       AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_atencion_id = ?  or '".$data['estado_atencion_id']."' IS NULL OR '".$data['estado_atencion_id']. "'=' '))
					    AND ((m.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					    AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }

         
           $sql.=" ORDER BY a.atencion_id desc ";
		 
		   $stm = $this->pdo->prepare($sql);
           
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id'] , $data['tipo_atencion_solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_solicitud_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio_solicitud'] ,
			      $data['fecha_fin_solicitud'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['tipo_atencion_solicitud_id'],
			      $data['especialidad_id'],
			      $data['estado_atencion_id'],
				  $data['medico_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador']));
			  }
           }
            
          //$data['tipo_atencion_id'],
		  
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
	

public function Reporte_Orden_Laboratorio($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = " SELECT distinct
					    mes(s.fecha_solicitud, 'es_ES') AS mes_solicitud,
					    mes(a.fecha_atencion, 'es_ES') AS mes_atencion,		
                 	  date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
					  s.solicitud_id,			  
			          a.atencion_id,
					  date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					 	 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,

			          e.nombre as especialidad_nombre,
					  CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  se.nombre as servicio_nombre,					  
					  es.nombre as estado_nombre,
					  cau.motivo as nombre_causa,
					  a.motivo, 
					  s.tipo_atencion_id as tipo_atencion_solicitud_id,	
					  ta1.nombre as tipo_atencion_solicitud,					  
					  ta.nombre as tipo_atencion_orden,
  					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,s.telefono2 as telefono2,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,
                      p.nombre as nombre_prioridad,
                      tc.nombre as nombre_tipo_consulta,
					  CASE 
					      s.tipo_atencion_id
							WHEN 5 THEN 'PROCEDIMIENTO' 
							WHEN 2 THEN 'CONSULTA / VESTIDA' 
							ELSE ''  
					  END as tipo_proceso,prov.nombre as nombre_proveedor
					   							 
			          FROM solicitud s  
			          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id
                      INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
					  INNER JOIN tipo_atencion ta1 on s.tipo_atencion_id=ta1.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id
                      INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id 
					  INNER JOIN prioridad p on p.prioridad_id = a.prioridad_id 
                      INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = a.tipo_consulta_id 
					  INNER JOIN proveedor prov on prov.proveedor_id = a.proveedor_id 
                      LEFT JOIN causa cau    on a.causa_id = cau.causa_id 
					  
					 
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? and s.tipo_atencion_id = ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and s.tipo_atencion_id = ? ";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND ( DATE(s.fecha_solicitud) between  ? and  ? OR  DATE(a.fecha_atencion) between  ? and  ?) )
				       AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_atencion_id = ?  or '".$data['estado_atencion_id']."' IS NULL OR '".$data['estado_atencion_id']. "'=' '))
					    AND ((m.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((prov.proveedor_id = ?  or '".$data['proveedor_id']."' IS NULL OR '".$data['proveedor_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					    AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }

          // AND ((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
           $sql.=" ORDER BY a.atencion_id desc ";
		 //  echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id'] , $data['tipo_atencion_solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_solicitud_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio_solicitud'] ,
			      $data['fecha_fin_solicitud'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['tipo_atencion_solicitud_id'],
			      $data['especialidad_id'],
			      $data['estado_atencion_id'],
				  $data['medico_id'],
				  $data['servicio_id'],
				  $data['proveedor_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador']));
			  }
           }
            
          //$data['tipo_atencion_id'],
		  
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
	
	
public function Reporte_Orden_Imagen($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = " SELECT distinct
					    mes(s.fecha_solicitud, 'es_ES') AS mes_solicitud,
					    mes(a.fecha_atencion, 'es_ES') AS mes_atencion,		
                  	  date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
					  s.solicitud_id,			  
			          a.atencion_id,
					  date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  	 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,

			          e.nombre as especialidad_nombre,
					  CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  se.nombre as servicio_nombre,					  
					  es.nombre as estado_nombre,
					  cau.motivo as nombre_causa,
					  a.motivo, 
					  s.tipo_atencion_id as tipo_atencion_solicitud_id,	
					  ta1.nombre as tipo_atencion_solicitud,					  
					  ta.nombre as tipo_atencion_orden,
  					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,s.telefono2 as telefono2,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,
                      p.nombre as nombre_prioridad,
                      tc.nombre as nombre_tipo_consulta,
					  CASE 
					      s.tipo_atencion_id
							WHEN 5 THEN 'PROCEDIMIENTO' 
							WHEN 2 THEN 'CONSULTA / VESTIDA' 
							ELSE ''  
					  END as tipo_proceso,prov.nombre as nombre_proveedor,
					  gr.nombre as nombre_grupo
					   							 
			          FROM solicitud s  
			          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id
                      INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
					  INNER JOIN tipo_atencion ta1 on s.tipo_atencion_id=ta1.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id
                      INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id 
					  INNER JOIN prioridad p on p.prioridad_id = a.prioridad_id 
                      INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = a.tipo_consulta_id
                      INNER JOIN grupo_servicio grser on grser.servicio_id = se.servicio_id
                      INNER JOIN grupo gr on gr.grupo_id = grser.grupo_id
                      INNER JOIN proveedor prov on prov.proveedor_id = a.proveedor_id 					  
                      LEFT JOIN causa cau    on a.causa_id = cau.causa_id 
					  
					 
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? and s.tipo_atencion_id = ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and s.tipo_atencion_id = ? ";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND ( DATE(s.fecha_solicitud) between  ? and  ? OR  DATE(a.fecha_atencion) between  ? and  ?) )
				       AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_atencion_id = ?  or '".$data['estado_atencion_id']."' IS NULL OR '".$data['estado_atencion_id']. "'=' '))
					    AND ((m.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((gr.grupo_id = ?  or '".$data['grupo_id']."' IS NULL OR '".$data['grupo_id']. "'=' '))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					    AND ((prov.proveedor_id = ?  or '".$data['proveedor_id']."' IS NULL OR '".$data['proveedor_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					    AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }

           $sql.=" ORDER BY a.atencion_id desc ";
		 //  echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id'] , $data['tipo_atencion_solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_solicitud_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio_solicitud'] ,
			      $data['fecha_fin_solicitud'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['tipo_atencion_solicitud_id'],
			      $data['especialidad_id'],
			      $data['estado_atencion_id'],
				  $data['medico_id'],
				  $data['grupo_id'],
				  $data['servicio_id'],
				  $data['proveedor_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador']));
			  }
           }
            
          //$data['tipo_atencion_id'],
		  
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
	
	
	
	
public function Reporte_Orden_put($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT  distinct
			          date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
					  a.motivo,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  	 b.nombres as nombre_beneficiario,
					  b.apellidos as nombre_titular,

					  s.solicitud_id,
					  a.atencion_id,
					  s.tipo_atencion_id as tipo_atencion_solicitud_id,
					  ta1.nombre as tipo_atencion_solicitud,
					  a.tipo_atencion_id as tipo_atencion_id,	
			          ta.nombre as tipo_atencion_nombre,
					  se.nombre as servicio_nombre,
			          CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre, 
					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,s.telefono2 as telefono2,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,a.is_automatica,a.tipo_atencion_id,
                      p.nombre as nombre_prioridad, 
                      tc.nombre as nombre_tipo_consulta,
					  CASE 
					      s.tipo_atencion_id
							WHEN 5 THEN 'PROCEDIMIENTO' 
							WHEN 2 THEN 'CONSULTA / VESTIDA' 
							ELSE ''  
					  END as tipo_proceso
                    
							 
			          FROM solicitud s  
			          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id
                      INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
					  INNER JOIN tipo_atencion ta1 on s.tipo_atencion_id=ta1.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id
                      INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id 
					  INNER JOIN prioridad p on p.prioridad_id = a.prioridad_id 
                      INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = a.tipo_consulta_id 
 
					 
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? and s.tipo_atencion_id = ?";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? and s.tipo_atencion_id = ? ";
				}
                else
				{
 
				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(a.fecha_atencion) between  ? and  ? )
				       AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_atencion_id not in (3,4) ))
					   AND ((m.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }

			// AND ((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
			
           $sql.=" ORDER BY a.atencion_id desc ";

		   $stm = $this->pdo->prepare($sql);

           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( 
				$data['solicitud_id'],$data['tipo_atencion_solicitud_id'])
				);
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_solicitud_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
				  $data['tipo_atencion_solicitud_id'],
			      $data['especialidad_id'],
				  $data['medico_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador']));
			  }
           }
            


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


public function Rep_orden_modificar_triaje($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			 $sql= "
					  SELECT distinct  a.beneficiario_id,a.solicitud_id,
					  s.atencion_id,
			          date_format(s.fecha_atencion, '%d/%m/%Y') AS fecha_atencion , 
			          tas.nombre as tipo_atencion_solicitud,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  	 b.nombres as nombre_beneficiario,
					  b.apellidos as nombre_titular,

			          ta.nombre as tipo_solicitud_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre,
                      a.telefono1 as telefono,a.telefono2 as telefono2,
					  CASE es.estado_solicitud_id 
					  when 2 then false
						 else
						     true end as estado,
                     a.usuario_creador,a.nombre_creador,
                     a.observacion,
					 tas.tipo_atencion_id as tipo_atencion_id,
                     tas.nombre as nombre_tipo_atencion	,
                     se.nombre as servicio_nom	,
					 gr.nombre as flag,
					 pr.nombre as proveedor_id,
					
					  a.motivo,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  b.nombres,
					  b.apellidos,
					  s.solicitud_id,
					  s.tipo_atencion_id as tipo_atencion_solicitud_id,
					  tas.nombre as tipo_atencion_solicitud,
					  a.tipo_atencion_id as tipo_atencion_id,	
			          ta.nombre as tipo_atencion_nombre,
					  se.nombre as servicio_nombre,
			         CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre, 
					  date_format(s.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      a.telefono1 as telefono,a.telefono2 as telefono2,	
							  CASE est.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,s.is_automatica,a.tipo_atencion_id,
                      p.nombre as nombre_prioridad, 
                      tc.nombre as nombre_tipo_consulta,
					  CASE 
					      s.tipo_atencion_id
							WHEN 5 THEN 'PROCEDIMIENTO' 
							WHEN 2 THEN 'CONSULTA / VESTIDA' 
							ELSE ''  
					  END as tipo_proceso
					 
                   FROM solicitud a
				       INNER JOIN atencion s on s.solicitud_id=a.solicitud_id 
				       INNER JOIN proveedor pr on pr.proveedor_id = s.proveedor_id
			          INNER JOIN tipo_atencion tas on tas.tipo_atencion_id = a.tipo_atencion_id and tas.tipo_solicitud_id = a.tipo_solicitud_id
                   INNER JOIN tipo_solicitud ta on ta.tipo_solicitud_id = a.tipo_solicitud_id
			          INNER JOIN estado_solicitud es on es.estado_solicitud_id =a.estado_solicitud_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
				       INNER JOIN beneficiario b on b.beneficiario_id  = a.beneficiario_id 
                   INNER join grupo_servicio g on g.servicio_id = s.servicio_id
                   INNER join servicio se on se.servicio_id =s.servicio_id and se.servicio_id = g.servicio_id
                   INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = s.tipo_consulta_id 
                   INNER JOIN estado_atencion est on est.estado_atencion_id =s.estado_atencion_id 
					    INNER JOIN prioridad p on p.prioridad_id = s.prioridad_id 
                   INNER join grupo gr on  gr.grupo_id = g.grupo_id
				   INNER JOIN medico m on m.medico_id = s.medico_id
			 ";
			 
					
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where s.atencion_id= ? and s.tipo_atencion_id = ? ";
				}
                else
				{
 
				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(s.fecha_atencion) between  ? and  ? )
				       AND ((s.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((pr.proveedor_id = ?  or '".$data['proveedor_id']."' IS NULL OR '".$data['proveedor_id']. "'=' ')) 
				       AND ((s.estado_atencion_id not in  (3,4) ))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(s.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   AND ((gr.grupo_id = ?  or '".$data['grupo_id']."' IS NULL OR '".$data['grupo_id']. "'=' '))
					   ";
				}
            }

		
           $sql.=" ORDER BY s.atencion_id desc ";
		   $stm = $this->pdo->prepare($sql);

		
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( 
				$data['solicitud_id'],$data['tipo_atencion_solicitud_id'])
				);
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_solicitud_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
				  $data['tipo_atencion_solicitud_id'],
			      $data['proveedor_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador'],
				  $data['grupo_id']));
			  }
           }
            
		   //$data['tipo_atencion_id'],

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
 
 public function Rep_orden_modificar_triaje_lab($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			 $sql= "SELECT distinct sol.beneficiario_id,sol.solicitud_id,
                    atenc.atencion_id,
                    date_format(atenc.fecha_atencion, '%d/%m/%Y') AS fecha_atencion , 
                    tas.nombre as tipo_atencion_solicitud,
                    b.cedula_titular,
                    b.cedula_hcm as cedula_beneficiario,
                    b.nombres,b.apellidos,
                    ta.nombre as tipo_solicitud_nombre,
                    e.nombre as especialidad_nombre,
                    est.nombre as estado_nombre,
                    sol.telefono1 as telefono,sol.telefono2 as telefono2,
                    CASE es.estado_solicitud_id 
                        when 2 then false
                                  else
                        true end as estado,
                    sol.usuario_creador,sol.nombre_creador,                   sol.observacion,
                    tas.tipo_atencion_id as tipo_atencion_id,                     tas.nombre as nombre_tipo_atencion              ,
                     se.nombre as servicio_nom   ,                     pr.nombre as proveedor_id,
                     sol.motivo,                     b.cedula_titular,
                     b.cedula_hcm as cedula_beneficiario,                     b.nombres,
                     b.apellidos,                     atenc.solicitud_id,
                     atenc.tipo_atencion_id as tipo_atencion_solicitud_id,                     tas.nombre as tipo_atencion_solicitud,
                     sol.tipo_atencion_id as tipo_atencion_id,          
                     ta.nombre as tipo_atencion_nombre,                                                                             se.nombre as servicio_nombre,
                                                         CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
                                                                                  e.nombre as especialidad_nombre,
                                                                                  es.nombre as estado_nombre, 
                                                                                  date_format(atenc.fecha_cita, '%d/%m/%Y') as       fecha_cita,
     
                                                                                                                  CASE est.estado_atencion_id 
                                                                                                                  when 2 then false
                                                                                                                  else
                                                                                                                     true end as estado,
                     atenc.is_automatica,sol.tipo_atencion_id,                     p.nombre as nombre_prioridad, 
                      tc.nombre as nombre_tipo_consulta,                                                                                  CASE 
                                                                                      atenc.tipo_atencion_id
                                                                                                                WHEN 5 THEN 'PROCEDIMIENTO' 
                                                                                                                WHEN 2 THEN 'CONSULTA / VESTIDA' 
                                                                                                                ELSE ''  
                                                                                  END as tipo_proceso
                                                                                
                   FROM solicitud sol
                                                                       INNER JOIN atencion atenc on atenc.solicitud_id=sol.solicitud_id 
                                                                       INNER JOIN proveedor pr on pr.proveedor_id = atenc.proveedor_id
                                                          INNER JOIN tipo_atencion tas on tas.tipo_atencion_id = sol.tipo_atencion_id and tas.tipo_solicitud_id = sol.tipo_solicitud_id
                   INNER JOIN tipo_solicitud ta on ta.tipo_solicitud_id = sol.tipo_solicitud_id
                                                          INNER JOIN estado_solicitud es on es.estado_solicitud_id =sol.estado_solicitud_id
                                                          INNER JOIN especialidad e on e.especialidad_id = sol.especialidad_id
                                                                       INNER JOIN beneficiario b on b.beneficiario_id  = sol.beneficiario_id 
                   INNER join servicio se on se.servicio_id =atenc.servicio_id 
                   INNER JOIN tipo_consulta tc on tc.tipo_consulta_id = atenc.tipo_consulta_id 
                   INNER JOIN estado_atencion est on est.estado_atencion_id =atenc.estado_atencion_id 
                                                                   INNER JOIN prioridad p on p.prioridad_id =atenc.prioridad_id 
                                                                   INNER JOIN medico m on m.medico_id = atenc.medico_id
			 ";
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where atenc.solicitud_id= ? and atenc.tipo_atencion_id = ?";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where atenc.atencion_id= ? and atenc.tipo_atencion_id = ? ";
				}
                else
				{
 
				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
				    $estado_atencion_id = $data['estado_atencion_id'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(atenc.fecha_atencion) between  ? and  ? )
				       AND ((atenc.tipo_atencion_id = ?  or '".$data['tipo_atencion_solicitud_id']."' IS NULL OR '".$data['tipo_atencion_solicitud_id']. "'=' ')) 
				       AND ((pr.proveedor_id = ?  or '".$data['proveedor_id']."' IS NULL OR '".$data['proveedor_id']. "'=' ')) 
				       AND ((atenc.estado_atencion_id not in  (3,4) ))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(atenc.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))
					   AND ((sol.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }

			// AND ((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
			
           $sql.=" ORDER BY atenc.atencion_id desc ";
		   //echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( 
				$data['solicitud_id'],$data['tipo_atencion_solicitud_id'])
				);
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id'],$data['tipo_atencion_solicitud_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
				  $data['tipo_atencion_solicitud_id'],
			      $data['proveedor_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['usuario_creador']));
			  }
           }
            
		   //$data['tipo_atencion_id'],

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


	
public function Reporte_Solicitud_put($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql = "  SELECT distinct  a.beneficiario_id,a.solicitud_id,
			          date_format(a.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud ,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  b.nombres ,
					  b.apellidos,
			          ta.nombre as tipo_solicitud_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre,
                      a.telefono1 as telefono,a.telefono2 as telefono2,
							  CASE es.estado_solicitud_id 
							  when 2 then false
							  else
							     true end as estado,
                      a.usuario_creador,a.nombre_creador,
                      a.observacion,
					  tas.tipo_atencion_id as tipo_atencion_id,
                      tas.nombre as nombre_tipo_atencion	
					  
			          FROM solicitud a  
			          INNER JOIN beneficiario b on b.beneficiario_id = a.beneficiario_id
                      INNER JOIN tipo_solicitud ta on a.tipo_solicitud_id=ta.tipo_solicitud_id
                      INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_solicitud es on es.estado_solicitud_id =a.estado_solicitud_id 
					  INNER JOIN tipo_atencion tas on tas.tipo_atencion_id = a.tipo_atencion_id  
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where a.solicitud_id= ?  ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? ";
				}
                else
				{
 				    $cedula =$data['cedula_beneficiario'];
				    $fecha_inicio = $data['fecha_inicio'];
				    $fecha_fin = $data['fecha_fin'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  ) 
                       AND DATE(a.fecha_solicitud) between  ? and  ? )
				       AND ((ta.tipo_solicitud_id = ?  or '".$data['tipo_solicitud_id']."' IS NULL OR '".$data['tipo_solicitud_id']. "'=' ')) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
				       AND ((es.estado_solicitud_id in (1,4) ))
					   AND ((a.usuario_creador = ?  or '".$data['usuario_creador']."' IS NULL OR '".$data['usuario_creador']. "'=' '))
					   ";
				}
            }
      
           $sql.=" ORDER BY a.solicitud_id desc ";
		  // echo $sql;
		   $stm = $this->pdo->prepare($sql);
           // echo $sql;
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['fecha_inicio'] ,
			      $data['fecha_fin'],
			      $data['tipo_solicitud_id'],
			      $data['especialidad_id'],
				  $data['usuario_creador']));
			  }
           }
            
          
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
	

public function Reporte_por_medico($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql= "  SELECT 
						0 as id,
						a.atencion_id,
						s.beneficiario_id,
			          date_format(a.fecha_atencion, '%d/%m/%Y') AS fecha_atencion ,
					  a.motivo,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  	 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo = 0 ) as nombre_titular,

					  s.solicitud_id,
			          ta.nombre as tipo_atencion_nombre,
					  se.nombre as servicio_nombre,
			          CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  e.nombre as especialidad_nombre,
			          es.nombre as estado_nombre,
					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado	,tu.nombre as turno			  					  
			          FROM solicitud s  
			          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id
                      INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id
                      INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id
					  INNER JOIN turno tu on tu.turno_id =a.turno_id
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? ";
				}
                else
				{
 
				    $cedula =$data['cedula_beneficiario'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  )) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
					   AND ((m.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' ')) 
                       AND es.estado_atencion_id = 2
					    AND ((a.turno_id = ?  or '".$data['turno_id']."' IS NULL OR '".$data['turno_id']. "'=' ')) ";
					if ($data['tipo_atencion_id']=="2")
					{
						$sql.="  AND (((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					    OR ((a.is_automatica   = ?  or '".$data['is_automatica']."' IS NULL OR '".$data['is_automatica']. "'=' '))) ";
					}
					else
					{
						if ($data['tipo_atencion_id']=="5")
						{
						   $sql.="  AND (((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					       AND ((a.is_automatica   = ?  or '".$data['is_automatica']."' IS NULL OR '".$data['is_automatica']. "'=' '))) ";
						}
						else
						{
						  $sql.="  AND (((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					       OR ((a.is_automatica   = ?  or '".$data['is_automatica']."' IS NULL OR '".$data['is_automatica']. "'=' '))) ";
						}

					}
				}
            }

          // echo $sql;
           $sql.=" order by s.beneficiario_id, se.servicio_id   ";
		  // echo $sql;
		   $stm = $this->pdo->prepare($sql);
         // 
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['especialidad_id'],
				  $data['medico_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['turno_id'],
				  $data['tipo_atencion_id'],
				  $data['is_automatica'] 
				 ));
				 
			  }
           }
            
          
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
 
 
 public function Reporte_por_cupos($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql= "  SELECT 
						0 as id,
						a.atencion_id,
						s.beneficiario_id,
			          date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_atencion ,
					  a.motivo,
			          b.cedula_titular,
					  b.cedula_hcm as cedula_beneficiario,
					  	 b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0 ) as nombre_titular,

					  s.solicitud_id,
			          ta.nombre as tipo_atencion_nombre,
					  se.nombre as servicio_nombre,
			          CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
					  e.nombre as especialidad_nombre,
			          es.nombre as estado_nombre,
					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
                      s.telefono1 as telefono,	
							  CASE es.estado_atencion_id 
							  when 2 then false
							  else
							     true end as estado	,tu.nombre as turno			  					  
			          FROM solicitud s  
			          INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id
                      INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id
                      INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			          INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id
					  INNER JOIN turno tu on tu.turno_id =a.turno_id
					  ";
			
            if (array_key_exists('solicitud_id',$data)) 
			{
               $sql.=" where s.solicitud_id= ? ";
            }
			else 
			{
				if (array_key_exists('atencion_id',$data)) 
				{
				   $sql.=" where a.atencion_id= ? ";
				}
                else
				{
 
				    $cedula =$data['cedula_beneficiario'];
                    $sql.=" where  
				       ((b.cedula_titular= ? or  '".$data['cedula_beneficiario']."' IS NULL OR '".$data['cedula_beneficiario']. "'=' '  )) 
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
					   AND ((m.medico_id = ?  or '".$data['medico_id']."' IS NULL OR '".$data['medico_id']. "'=' '))
					   AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' ')) 
                       AND es.estado_atencion_id = 2
					    AND ((a.turno_id = ?  or '".$data['turno_id']."' IS NULL OR '".$data['turno_id']. "'=' ')) ";
					if ($data['tipo_atencion_id']=="2")
					{
						$sql.="  AND (((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					    OR ((a.is_automatica   = ?  or '".$data['is_automatica']."' IS NULL OR '".$data['is_automatica']. "'=' '))) ";
					}
					else
					{
						if ($data['tipo_atencion_id']=="5")
						{
						   $sql.="  AND (((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					       AND ((a.is_automatica   = ?  or '".$data['is_automatica']."' IS NULL OR '".$data['is_automatica']. "'=' '))) ";
						}
						else
						{
						  $sql.="  AND (((ta.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) 
					       OR ((a.is_automatica   = ?  or '".$data['is_automatica']."' IS NULL OR '".$data['is_automatica']. "'=' '))) ";
						}

					}
				}
            }

          // echo $sql;
           $sql.=" order by s.beneficiario_id, se.servicio_id   ";
		  // echo $sql;
		 $stm = $this->pdo->prepare($sql);
         // 
           if (array_key_exists('solicitud_id',$data)) 
		   {
                $stm->execute( array( $data['solicitud_id']));
           }
		   else
		   {
			   if (array_key_exists('atencion_id',$data)) 
		       {
                $stm->execute( array( $data['atencion_id']));
               }
		       else
			   {
			      $stm->execute( array( 
                  $data['cedula_beneficiario'],
			      $data['especialidad_id'],
				  $data['medico_id'],
				  $data['servicio_id'],
				  $data['fecha_cita'],
				  $data['turno_id'],
				  $data['tipo_atencion_id'],
				  $data['is_automatica'] 
				 ));
				 
			  }
           }
            
          
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

public function Reporte_por_cupos_Lab($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql= "   SELECT DISTINCT 0 as id,
			          a.atencion_id,
					  s.beneficiario_id,
					  date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_atencion ,
					  b.cedula_titular, b.cedula_hcm as cedula_beneficiario,
					  b.nombres as nombre_beneficiario,
					  (select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0) as nombre_titular,
                      s.solicitud_id,
					  ta.nombre as tipo_atencion_nombre,
		              se.nombre as servicio_nombre,
					  m.nombres  as medico_nombre,
					  e.nombre as especialidad_nombre,
					  es.nombre as estado_nombre,
					  date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
					  s.telefono1 as telefono,
					  tu.nombre as turno,
					  gr.nombre as grupo,
					  ex.nombre as examen,
					  pri.nombre as prioridad_id  
			       FROM solicitud s  INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id         INNER JOIN atencion a on s.solicitud_id=a.solicitud_id  INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
                   INNER JOIN servicio se on se.servicio_id = a.servicio_id  INNER JOIN medico m on m.medico_id = a.medico_id  INNER JOIN proveedor prov on prov.proveedor_id = a.proveedor_id   INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id
			       INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id   INNER JOIN turno tu on tu.turno_id =a.turno_id   INNER JOIN atencion_examen atex on atex.atencion_id = a.atencion_id  INNER JOIN examen ex on atex.examen_id = ex.examen_id
				   INNER JOIN grupo_examen gr_ex on gr_ex.examen_id = ex.examen_id    INNER JOIN grupo gr on gr.grupo_id = gr_ex.grupo_id  INNER JOIN prioridad pri on pri.prioridad_id=a.prioridad_id
					  ";
             $sql.="   WHERE (a.tipo_atencion_id = ?  )  AND ((prov.proveedor_id = ?  or '".$data['proveedor_id']."' IS NULL OR '".$data['proveedor_id']. "'=' '))
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' '))    AND ((gr.grupo_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' '))   AND es.estado_atencion_id = 2  AND ((a.turno_id = ?  or '".$data['turno_id']."' IS NULL OR '".$data['turno_id']. "'=' ')) ";

          // echo $sql;
           $sql.=" order by s.beneficiario_id,a.atencion_id asc,gr.nombre, ex.nombre asc   ";
		
		$stm = $this->pdo->prepare($sql);
  	     $stm->execute( array( 
				   $data['tipo_atencion_id'],
				   $data['proveedor_id'],
                   $data['especialidad_id'],
	 			   $data['servicio_id'],
				   $data['fecha_cita'],
				   $data['turno_id']
				 ));
          
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
 
 public function Reporte_por_cupos_triaje($data){
        try
        {
             $result = array();
			 
		     $this->pdo = parent::conexion();
			 
			$sql= "  SELECT  0 as id,
			a.atencion_id,
			s.beneficiario_id, 
			date_format(s.fecha_solicitud, '%d/%m/%Y') AS fecha_atencion ,
			a.motivo,
			b.cedula_titular,
			b.cedula_hcm as cedula_beneficiario,
			b.nombres as nombre_beneficiario,
			(select distinct b1.nombres as apellidos from beneficiario b1 where b1.cedula_titular=b.cedula_titular and b1.cedula_titular=b1.cedula_beneficiario and consecutivo=0) as nombre_titular,
            s.solicitud_id,
			ta.nombre as tipo_atencion_nombre,
			se.nombre as servicio_nombre,
			CONCAT(m.nombres,' ',m.apellidos)  as medico_nombre,
			e.nombre as especialidad_nombre,
			es.nombre as estado_nombre,
			date_format(a.fecha_cita, '%d/%m/%Y') as 	fecha_cita,
            s.telefono1 as telefono,
			CASE es.estado_atencion_id  when 2 then false  else    true end as estado	,
			tu.nombre as turno,
            pri.nombre as prioridad_id ,
			gr.nombre as grupo,prov.nombre as nombre_proveedor 
			FROM solicitud s  INNER JOIN beneficiario b on b.beneficiario_id = s.beneficiario_id
                      INNER JOIN atencion a on s.solicitud_id=a.solicitud_id             INNER JOIN tipo_atencion ta on a.tipo_atencion_id=ta.tipo_atencion_id
                      INNER JOIN servicio se on se.servicio_id = a.servicio_id           INNER JOIN medico m on m.medico_id = a.medico_id
			          INNER JOIN especialidad e on e.especialidad_id = a.especialidad_id INNER JOIN estado_atencion es on es.estado_atencion_id =a.estado_atencion_id
					  INNER JOIN turno tu on tu.turno_id =a.turno_id  			          INNER JOIN prioridad pri on pri.prioridad_id=a.prioridad_id
					  INNER JOIN proveedor prov on prov.proveedor_id = a.proveedor_id     INNER JOIN grupo_servicio gr_ex on gr_ex.servicio_id = se.servicio_id  
                      INNER JOIN grupo gr on gr.grupo_id = gr_ex.grupo_id
					  ";
				   $sql.="  WHERE (((a.tipo_atencion_id = ?  or '".$data['tipo_atencion_id']."' IS NULL OR '".$data['tipo_atencion_id']. "'=' ')) ) ";
                    $sql.=" AND  ((a.proveedor_id = ?  or '".$data['proveedor_id']."' IS NULL OR '".$data['proveedor_id']. "'=' '))
				       AND ((e.especialidad_id = ?  or '".$data['especialidad_id']."' IS NULL OR '".$data['especialidad_id']. "'=' ')) 
					   AND ((gr.grupo_id = ?  or '".$data['grupo_id']."' IS NULL OR '".$data['grupo_id']. "'=' '))
					    AND ((se.servicio_id = ?  or '".$data['servicio_id']."' IS NULL OR '".$data['servicio_id']. "'=' '))
					   AND ((DATE(a.fecha_cita) = ?  or '".$data['fecha_cita']."' IS NULL OR '".$data['fecha_cita']. "'=' ')) 
                       AND es.estado_atencion_id = 2
					   AND ((a.turno_id = ?  or '".$data['turno_id']."' IS NULL OR '".$data['turno_id']. "'=' ')) ";
	

           $sql.=" order by s.beneficiario_id,a.atencion_id ,gr.nombre, se.nombre asc   ";
		  // echo $sql;
		 $stm = $this->pdo->prepare($sql);
         // 
			      $stm->execute( array( 
				   $data['tipo_atencion_id'],
				   $data['proveedor_id'],
                   $data['especialidad_id'],
				   $data['grupo_id'],
	 			   $data['servicio_id'],
				   $data['fecha_cita'],
				   $data['turno_id'],
				 
				 
				 ));
          
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
            $stm = $this->pdo->prepare("select distinct nombre_creador as label, usuario_creador as value  
										FROM solicitud");
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
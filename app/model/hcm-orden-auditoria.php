<?php
namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use App\Lib\Response;
use App\Lib\Connection;
use PDO;
use PDOException;


class OrdenAuditoriaModel extends Connection
{
	
	 public $response;
	 
	  public function __construct()
	  {
        $this->response = new Response();
      }
	  
	  
	
public function Reporte_auditoria_orden($data){
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
	
	
	public function getAllCombo(){
        try
        {
            $this->pdo = parent::conexion();
            $stm = $this->pdo->prepare("select distinct nombre_creador as label, usuario_creador as value  
										FROM atencion");
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
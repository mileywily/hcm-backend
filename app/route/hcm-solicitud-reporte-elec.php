<?php

use App\Model\SolicitudElecReportModel;

$app->group('/api/', function () {
    
    $this->get('reportes_sol_elec/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    


    //Reporte de Solicitudes  
    $this->post('reportes_sol_elec/repor_sol', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_Solicitud_Servicio(
                    $req->getParsedBody()
                )
            )
        );
    });
	
  //Reporte de Solicitudes  
    $this->post('reportes_sol_elec/repor_sol_abierta', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_Solicitud_Abiertas(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	  //Reporte de Solicitudes  
    $this->post('reportes_sol_elec/repor_sol_abierta_imagen', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_Solicitud_Abiertas_Imagen(
                    $req->getParsedBody()
                )
            )
        );
    });
	
	  //Reporte de Solicitudes  abiertas Laboratorio
    $this->post('reportes_sol_elec/repor_sol_abierta_lab', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_Solicitud_Abiertas_Lab(
                    $req->getParsedBody()
                )
            )
        );
    });	
	

    //Reporte de Orden  
    $this->post('reportes_sol_elec/reporte_orden', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_Orden(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	    //Reporte de Orden  Laboratorio
    $this->post('reportes_sol_elec/reporte_orden_lab', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_Orden_Laboratorio(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
		    //Reporte de Orden  Laboratorio
    $this->post('reportes_sol_elec/reporte_orden_img', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_Orden_Imagen(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	
	
		  //Reporte de Solicitud Modificar  
    $this->post('reportes_sol_elec/solicitud_modificar', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_Solicitud_put(
                    $req->getParsedBody()
                )
            )
        );
    });
	
	
	  //Reporte de Orden Modificar  
    $this->post('reportes_sol_elec/orden_modificar', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_Orden_put(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
    //Reporte de Orden  
    $this->post('reportes_sol_elec/repor_medico', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_medico(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	    //Reporte de Cupos 
    $this->post('reportes_sol_elec/repor_cupos', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_cupos(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	    //Reporte de Cupos 
    $this->post('reportes_sol_elec/repor_cupos_lab', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_cupos_Lab(
                    $req->getParsedBody()
                )
            )
        );
    });	


		    //Reporte de Cupos 
    $this->post('reportes_sol_elec/repor_cupos_triaje', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_por_cupos_triaje(
                    $req->getParsedBody()
                )
            )
        );
    });	
	

    //Obtener todos los registros para lista en combos
    $this->get('reportes_sol_elec/listar_user', function ($req, $res, $args) {

        $um = new SolicitudElecReportModel();
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(    
                $um->getAllCombo()
            )
        );
    });	
	
	
	///
    //Reporte de Orden Por atencion Modificar  
    
	$this->post('reportes_sol_elec/ordenbyatencion_modificar', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_Orden_put(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	
		$this->post('reportes_sol_elec/rep_orden_modificar_triaje', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Rep_orden_modificar_triaje(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	
 $this->post('reportes_sol_elec/rep_orden_modificar_triaje_lab', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Rep_orden_modificar_triaje_lab(
                    $req->getParsedBody()
                )
            )
        );
    });	
	
	
//Reporte de Solicitud Modificar  por tipo de atencion
    $this->post('reportes_sol_elec/solicitudbyatencion_modificar', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_Solicitud_By_Atencion(
                    $req->getParsedBody()
                )
            )
        );
    });
	
//Reporte de Solicitud Triaje Modificar  por tipo de atencion
    $this->post('reportes_sol_elec/rep_solicitud_modificar_triaje', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Rep_Solic_Triaje_By_Atencion(
                    $req->getParsedBody()
                )
            )
        );
    });
	
	
	//Reporte de Solicitud Triaje Lab Modificar  por tipo de atencion
    $this->post('reportes_sol_elec/rep_solicitud_modificar_triajeLab', function ($req, $res, $args){
        $um = new SolicitudElecReportModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Rep_Solic_TriajeLab_By_Atencion(
                    $req->getParsedBody()
                )
            )
        );
    });
	
    
});
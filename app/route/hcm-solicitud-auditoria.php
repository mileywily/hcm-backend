<?php

use App\Model\SolicitudAuditoriaModel;

$app->group('/api/', function () {
    
    $this->get('reportes_audi_solicitud/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    

    //Reporte de Orden  
    $this->post('reportes_audi_solicitud/auditoria', function ($req, $res, $args){
        $um = new SolicitudAuditoriaModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_auditoria_solicitud(
                    $req->getParsedBody()
                )
            )
        );
    });	
	

    //Obtener todos los registros para lista en combos
    $this->get('reportes_audi_solicitud/listar_user', function ($req, $res, $args) {

        $um = new SolicitudAuditoriaModel();
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(    
                $um->getAllCombo()
            )
        );
    });	
	
	

	
	

	
	

	
	

	

	

	
    
});
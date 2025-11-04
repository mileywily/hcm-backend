<?php

use App\Model\OrdenAuditoriaModel;

$app->group('/api/', function () {
    
    $this->get('reportes_audi_orden/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    

    //Reporte de Orden  
    $this->post('reportes_audi_orden/auditoria', function ($req, $res, $args){
        $um = new OrdenAuditoriaModel();
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->Reporte_auditoria_orden(
                    $req->getParsedBody()
                )
            )
        );
    });	
	

    //Obtener todos los registros para lista en combos
    $this->get('reportes_audi_orden/listar_user', function ($req, $res, $args) {

        $um = new OrdenAuditoriaModel();
        
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

	
	

	
	

	
	

	

	

	
    
});
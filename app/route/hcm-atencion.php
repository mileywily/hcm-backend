<?php

use App\Model\AtencionModel;

$app->group('/api/', function () {
    
    $this->get('atenciones/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('atenciones', function ($req, $res, $args) {
        
        $um = new AtencionModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });


    //Obtener registro por id
    $this->get('atenciones/{id}', function ($req, $res, $args) {
        
		$um = new AtencionModel(null);
        $um->atencion_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->atencion_id)
            )
        );
    });

    //Consultar ordenes por solicitud
    $this->post('atenciones/solicitud', function ($req, $res) {
        $um = new AtencionModel($req->getParsedBody());
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getOrdenesBySolicitud()
            )
        );
    });
	
    //Consultar ordenes por solicitud y Estatus
    $this->post('atenciones/solicitudEstatus', function ($req, $res) {
        $um = new AtencionModel($req->getParsedBody());
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getOrdenesBySolicitudEstatus()
            )
        );
    });	
	
	
 //Consultar ordenes por Orden
    $this->post('atenciones/orden', function ($req, $res) {
        $um = new AtencionModel($req->getParsedBody());
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getOrdenesByOrden()
            )
        );
    });	
	
	 //Insertar registro
    $this->post('atenciones', function ($req, $res) {
         
		 $um = new AtencionModel($req->getParsedBody());
                
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->create()
            )
        );
    });
	


    //Actualizar registro
    $this->put('atenciones', function ($req, $res) {
        
		$um = new AtencionModel($req->getParsedBody());
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->update()
            )
        );
    });
    
    //Eliminar registro por id
    //
    $this->delete('atenciones/{id}', function ($req, $res, $args) {
        
		$um = new AtencionModel(null);
        $um->atencion_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->atencion_id)
            )
        );
    });

    $this->delete('atenciones', function ($req, $res) {
        
		$um = new AtencionModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->deleteAll()
            )
        );
    });


    //Eliminar registros por lote 
    $this->post('atenciones/eliminarLote', function ($req, $res) {
        $um = new AtencionModel(null);
        
        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->deleteByLote(
                    $req->getParsedBody()
                )
            )
        );
    }); 

    //Insertar registro ordenes de servicio
    $this->post('atenciones/crearorden', function ($req, $res) {

        $um = new AtencionModel($req->getParsedBody());
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->crearOrdenesServicio()
            )
        );
    });

    //cancelar ordenes de servicio
    $this->post('atenciones/cancelarorden', function ($req, $res) {

        $um = new AtencionModel($req->getParsedBody());
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->cancelarOrden()
            )
        );
    });

    //procesar ordenes de servicio
    $this->post('atenciones/procesarorden', function ($req, $res) {

        $um = new AtencionModel($req->getParsedBody());
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->procesarOrden()
            )
        );
    });
    
});
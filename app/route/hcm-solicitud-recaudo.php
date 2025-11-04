<?php

use App\Model\SolicitudRecaudoModel;

$app->group('/api/', function () {
    
    $this->get('solicitudrecaudos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('solicitudrecaudos', function ($req, $res, $args) {
        
        $um = new SolicitudRecaudoModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    //Obtener todos los registros para lista en combos
    $this->get('solicitudrecaudos/listar', function ($req, $res, $args) {

        $um = new SolicitudRecaudoModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Servicios asociados a la solicitud en la relacion servicio_tipoatencion
    $this->get('solicitudrecaudos/solicitud/{id}', function ($req, $res, $args) {
        
        $um = new SolicitudRecaudoModel(null);
        $um->solicitud_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getRecaudosBySolicitud()
            )
        );
    });

    //Obtener registro por id
    $this->get('solicitudrecaudos/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudRecaudoModel(null);
        $um->solicitud_recaudo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->solicitud_recaudo_id)
            )
        );
    });

    //Insertar registro
    $this->post('solicitudrecaudos', function ($req, $res) {
         
		 $um = new SolicitudRecaudoModel($req->getParsedBody());
                
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
    $this->put('solicitudrecaudos', function ($req, $res) {
        
		$um = new SolicitudRecaudoModel($req->getParsedBody());
        
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
    $this->delete('solicitudrecaudos/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudRecaudoModel(null);
        $um->solicitud_recaudo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->solicitud_recaudo_id)
            )
        );
    });

    $this->delete('solicitudrecaudos', function ($req, $res) {
        
		$um = new SolicitudRecaudoModel(null);
        
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
    $this->post('solicitudrecaudos/eliminarLote', function ($req, $res) {
        $um = new SolicitudRecaudoModel(null);
        
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
    
});
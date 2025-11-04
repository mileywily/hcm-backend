<?php

use App\Model\EstadoSolicitudModel;

$app->group('/api/', function () {
    
    $this->get('estadosolicitudes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('estadosolicitudes', function ($req, $res, $args) {
        
        $um = new EstadoSolicitudModel(null);
        
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
    $this->get('estadosolicitudes/listar', function ($req, $res, $args) {

        $um = new EstadoSolicitudModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Obtener registro por id
    $this->get('estadosolicitudes/{id}', function ($req, $res, $args) {
        
		$um = new EstadoSolicitudModel(null);
        $um->estado_solicitud_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->estado_solicitud_id)
            )
        );
    });


    //Insertar registro
    $this->post('estadosolicitudes', function ($req, $res) {
         
		 $um = new EstadoSolicitudModel($req->getParsedBody());
                
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
    $this->put('estadosolicitudes', function ($req, $res) {
        
		$um = new EstadoSolicitudModel($req->getParsedBody());
        
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
    $this->delete('estadosolicitudes/{id}', function ($req, $res, $args) {
        
		$um = new EstadoSolicitudModel(null);
        $um->estado_solicitud_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->estado_solicitud_id)
            )
        );
    });

    $this->delete('estadosolicitudes', function ($req, $res) {
        
		$um = new EstadoSolicitudModel(null);
        
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
    $this->post('estadosolicitudes/eliminarLote', function ($req, $res) {
        $um = new EstadoSolicitudModel(null);
        
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
<?php

use App\Model\TipoAtencionModel;

$app->group('/api/', function () {
    
    $this->get('tipoatenciones/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipoatenciones', function ($req, $res, $args) {
        
        $um = new TipoAtencionModel(null);
        
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
    $this->get('tipoatenciones/listar', function ($req, $res, $args) {

        $um = new TipoAtencionModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Obtener todos los registros para lista en combos
    $this->get('tipoatenciones/listaractivos', function ($req, $res, $args) {

        $um = new TipoAtencionModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllActivos()
            )
        );
    });

    //Obtener registro por id
    $this->get('tipoatenciones/{id}', function ($req, $res, $args) {
        
		$um = new TipoAtencionModel(null);
        $um->tipo_atencion_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_atencion_id)
            )
        );
    });

    
    //Obtener registro por tipo de solicitud
    $this->get('tipoatenciones/tiposolicitud/{id}', function ($req, $res, $args) {
        
		$um = new TipoAtencionModel(null);
        $um->tipo_solicitud_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getAllByTipoSolicitud($um->tipo_solicitud_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipoatenciones', function ($req, $res) {
         
		 $um = new TipoAtencionModel($req->getParsedBody());
                
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
    $this->put('tipoatenciones', function ($req, $res) {
        
		$um = new TipoAtencionModel($req->getParsedBody());
        
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
    $this->delete('tipoatenciones/{id}', function ($req, $res, $args) {
        
		$um = new TipoAtencionModel(null);
        $um->tipo_atencion_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_atencion_id)
            )
        );
    });

    $this->delete('tipoatenciones', function ($req, $res) {
        
		$um = new TipoAtencionModel(null);
        
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
    $this->post('tipoatenciones/eliminarLote', function ($req, $res) {
        $um = new TipoAtencionModel(null);
        
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
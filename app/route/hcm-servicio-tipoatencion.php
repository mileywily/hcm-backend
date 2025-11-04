<?php

use App\Model\ServicioTipoModel;

$app->group('/api/', function () {
    
    $this->get('serviciotipoatenciones/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('serviciotipoatenciones', function ($req, $res, $args) {
        
        $um = new ServicioTipoModel(null);
        
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
    $this->get('serviciotipoatenciones/listar', function ($req, $res, $args) {

        $um = new ServicioTipoModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Servicios asociados a la atencion
    $this->get('serviciotipoatenciones/tipoatencion/{id}', function ($req, $res, $args) {
        
        $um = new ServicioTipoModel(null);
        $um->tipo_atencion_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByAtencion()
            )
        );
    });

    //Obtener registro por id
    $this->get('serviciotipoatenciones/{id}', function ($req, $res, $args) {
        
		$um = new ServicioTipoModel(null);
        $um->servicio_tipoatencion_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->servicio_tipoatencion_id)
            )
        );
    });

    //Insertar registro
    $this->post('serviciotipoatenciones', function ($req, $res) {
         
		 $um = new ServicioTipoModel($req->getParsedBody());
                
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
    $this->put('serviciotipoatenciones', function ($req, $res) {
        
		$um = new ServicioTipoModel($req->getParsedBody());
        
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
    $this->delete('serviciotipoatenciones/{id}', function ($req, $res, $args) {
        
		$um = new ServicioTipoModel(null);
        $um->servicio_tipoatencion_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->servicio_tipoatencion_id)
            )
        );
    });

    $this->delete('serviciotipoatenciones', function ($req, $res) {
        
		$um = new ServicioTipoModel(null);
        
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
    $this->post('serviciotipoatenciones/eliminarLote', function ($req, $res) {
        $um = new ServicioTipoModel(null);
        
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
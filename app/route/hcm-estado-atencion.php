<?php

use App\Model\EstadoAtencionModel;

$app->group('/api/', function () {
    
    $this->get('estadoatenciones/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('estadoatenciones', function ($req, $res, $args) {
        
        $um = new EstadoAtencionModel(null);
        
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
    $this->get('estadoatenciones/listar', function ($req, $res, $args) {
    
        $um = new EstadoAtencionModel(null);
        
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
    $this->get('estadoatenciones/{id}', function ($req, $res, $args) {
        
		$um = new EstadoAtencionModel(null);
        $um->estado_atencion_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->estado_atencion_id)
            )
        );
    });

    //Insertar registro
    $this->post('estadoatenciones', function ($req, $res) {
         
		 $um = new EstadoAtencionModel($req->getParsedBody());
                
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
    $this->put('estadoatenciones', function ($req, $res) {
        
		$um = new EstadoAtencionModel($req->getParsedBody());
        
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
    $this->delete('estadoatenciones/{id}', function ($req, $res, $args) {
        
		$um = new EstadoAtencionModel(null);
        $um->estado_atencion_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->estado_atencion_id)
            )
        );
    });

    $this->delete('estadoatenciones', function ($req, $res) {
        
		$um = new EstadoAtencionModel(null);
        
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
    $this->post('estadoatenciones/eliminarLote', function ($req, $res) {
        $um = new EstadoAtencionModel(null);
        
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
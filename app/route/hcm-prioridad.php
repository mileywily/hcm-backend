<?php

use App\Model\PrioridadModel;

$app->group('/api/', function () {
    
    $this->get('prioridades/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('prioridades', function ($req, $res, $args) {
        
        $um = new PrioridadModel(null);
        
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
    $this->get('prioridades/listar', function ($req, $res, $args) {

        $um = new PrioridadModel(null);
        
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
    $this->get('prioridades/{id}', function ($req, $res, $args) {
        
		$um = new PrioridadModel(null);
        $um->prioridad_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->prioridad_id)
            )
        );
    });

    //Insertar registro
    $this->post('prioridades', function ($req, $res) {
         
		 $um = new PrioridadModel($req->getParsedBody());
                
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
    $this->put('prioridades', function ($req, $res) {
        
		$um = new PrioridadModel($req->getParsedBody());
        
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
    $this->delete('prioridades/{id}', function ($req, $res, $args) {
        
		$um = new PrioridadModel(null);
        $um->prioridad_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->prioridad_id)
            )
        );
    });

    $this->delete('prioridades', function ($req, $res) {
        
		$um = new PrioridadModel(null);
        
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
    $this->post('prioridades/eliminarLote', function ($req, $res) {
        $um = new PrioridadModel(null);
        
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
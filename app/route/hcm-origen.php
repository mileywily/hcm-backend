<?php

use App\Model\OrigenModel;

$app->group('/api/', function () {
    
    $this->get('origenes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('origenes', function ($req, $res, $args) {
        
        $um = new OrigenModel(null);
        
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
    $this->get('origenes/{id}', function ($req, $res, $args) {
        
		$um = new OrigenModel(null);
        $um->origen_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->origen_id)
            )
        );
    });

    //Insertar registro
    $this->post('origenes', function ($req, $res) {
         
		 $um = new OrigenModel($req->getParsedBody());
                
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
    $this->put('origenes', function ($req, $res) {
        
		$um = new OrigenModel($req->getParsedBody());
        
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
    $this->delete('origenes/{id}', function ($req, $res, $args) {
        
		$um = new OrigenModel(null);
        $um->origen_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->origen_id)
            )
        );
    });

    $this->delete('origenes', function ($req, $res) {
        
		$um = new OrigenModel(null);
        
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
    $this->post('origenes/eliminarLote', function ($req, $res) {
        $um = new OrigenModel(null);
        
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
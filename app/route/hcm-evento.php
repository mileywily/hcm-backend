<?php

use App\Model\EventoModel;

$app->group('/api/', function () {
    
    $this->get('eventos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('eventos', function ($req, $res, $args) {
        
        $um = new EventoModel(null);
        
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
    $this->get('eventos/{id}', function ($req, $res, $args) {
        
		$um = new EventoModel(null);
        $um->evento_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->evento_id)
            )
        );
    });

    //Insertar registro
    $this->post('eventos', function ($req, $res) {
         
		 $um = new EventoModel($req->getParsedBody());
                
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
    $this->put('eventos', function ($req, $res) {
        
		$um = new EventoModel($req->getParsedBody());
        
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
    $this->delete('eventos/{id}', function ($req, $res, $args) {
        
		$um = new EventoModel(null);
        $um->evento_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->evento_id)
            )
        );
    });

    $this->delete('eventos', function ($req, $res) {
        
		$um = new EventoModel(null);
        
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
    $this->post('eventos/eliminarLote', function ($req, $res) {
        $um = new EventoModel(null);
        
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
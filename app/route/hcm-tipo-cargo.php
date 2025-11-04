<?php

use App\Model\TipoCargoModel;

$app->group('/api/', function () {
    
    $this->get('tipocargos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipocargos', function ($req, $res, $args) {
        
        $um = new TipoCargoModel(null);
        
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
    $this->get('tipocargos/{id}', function ($req, $res, $args) {
        
		$um = new TipoCargoModel(null);
        $um->tipo_cargo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_cargo_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipocargos', function ($req, $res) {
         
		 $um = new TipoCargoModel($req->getParsedBody());
                
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
    $this->put('tipocargos', function ($req, $res) {
        
		$um = new TipoCargoModel($req->getParsedBody());
        
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
    $this->delete('tipocargos/{id}', function ($req, $res, $args) {
        
		$um = new TipoCargoModel(null);
        $um->tipo_cargo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_cargo_id)
            )
        );
    });

    $this->delete('tipocargos', function ($req, $res) {
        
		$um = new TipoCargoModel(null);
        
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
    $this->post('tipocargos/eliminarLote', function ($req, $res) {
        $um = new TipoCargoModel(null);
        
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
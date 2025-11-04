<?php

use App\Model\TipoCupoModel;

$app->group('/api/', function () {
    
    $this->get('tipocupos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });

    //Obtener todos los registros para lista en combos
    $this->get('tipocupos/listar', function ($req, $res, $args) {

        $um = new TipoCupoModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });
    
    //Obtener todos los registros
    $this->get('tipocupos', function ($req, $res, $args) {
        
        $um = new TipoCupoModel(null);
        
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
    $this->get('tipocupos/{id}', function ($req, $res, $args) {
        
		$um = new TipoCupoModel(null);
        $um->tipo_cupo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_cupo_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipocupos', function ($req, $res) {
         
		 $um = new TipoCupoModel($req->getParsedBody());
                
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
    $this->put('tipocupos', function ($req, $res) {
        
		$um = new TipoCupoModel($req->getParsedBody());
        
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
    $this->delete('tipocupos/{id}', function ($req, $res, $args) {
        
		$um = new TipoCupoModel(null);
        $um->tipo_cupo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_cupo_id)
            )
        );
    });

    $this->delete('tipocupos', function ($req, $res) {
        
		$um = new TipoCupoModel(null);
        
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
    $this->post('tipocupos/eliminarLote', function ($req, $res) {
        $um = new TipoCupoModel(null);
        
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
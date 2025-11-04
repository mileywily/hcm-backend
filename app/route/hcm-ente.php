<?php

use App\Model\EnteModel;

$app->group('/api/', function () {
    
    $this->get('entes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('entes', function ($req, $res, $args) {
        
        $um = new EnteModel(null);
        
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
    $this->get('entes/listar', function ($req, $res, $args) {

        $um = new EnteModel(null);
        
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
    $this->get('entes/{id}', function ($req, $res, $args) {
        
		$um = new EnteModel(null);
        $um->ente_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->ente_id)
            )
        );
    });

    //Insertar registro
    $this->post('entes', function ($req, $res) {
         
		 $um = new EnteModel($req->getParsedBody());
                
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
    $this->put('entes', function ($req, $res) {
        
		$um = new EnteModel($req->getParsedBody());
        
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
    $this->delete('entes/{id}', function ($req, $res, $args) {
        
		$um = new EnteModel(null);
        $um->ente_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->ente_id)
            )
        );
    });

    $this->delete('entes', function ($req, $res) {
        
		$um = new EnteModel(null);
        
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
    $this->post('entes/eliminarLote', function ($req, $res) {
        $um = new EnteModel(null);
        
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
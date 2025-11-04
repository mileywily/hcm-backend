<?php

use App\Model\ExamenModel;

$app->group('/api/', function () {
    
    $this->get('examenes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('examenes', function ($req, $res, $args) {
        
        $um = new ExamenModel(null);
        
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
    $this->get('examenes/listar', function ($req, $res, $args) {

        $um = new ExamenModel(null);
        
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
    $this->get('examenes/{id}', function ($req, $res, $args) {
        
		$um = new ExamenModel(null);
        $um->examen_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->examen_id)
            )
        );
    });



    //Insertar registro
    $this->post('examenes', function ($req, $res) {
         
		 $um = new ExamenModel($req->getParsedBody());
                
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
    $this->put('examenes', function ($req, $res) {
        
		$um = new ExamenModel($req->getParsedBody());
        
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
    $this->delete('examenes/{id}', function ($req, $res, $args) {
        
		$um = new ExamenModel(null);
        $um->examen_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->examen_id)
            )
        );
    });

    $this->delete('examenes', function ($req, $res) {
        
		$um = new ExamenModel(null);
        
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
    $this->post('examenes/eliminarLote', function ($req, $res) {
        $um = new ExamenModel(null);
        
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
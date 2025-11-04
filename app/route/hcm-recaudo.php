<?php

use App\Model\RecaudoModel;

$app->group('/api/', function () {
    
    $this->get('recaudos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('recaudos', function ($req, $res, $args) {
        
        $um = new RecaudoModel(null);
        
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
    $this->get('recaudos/listar', function ($req, $res, $args) {

        $um = new RecaudoModel(null);
        
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
    $this->get('recaudos/{id}', function ($req, $res, $args) {
        
		$um = new RecaudoModel(null);
        $um->recaudo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->recaudo_id)
            )
        );
    });

    //Insertar registro
    $this->post('recaudos', function ($req, $res) {
         
		 $um = new RecaudoModel($req->getParsedBody());
                
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
    $this->put('recaudos', function ($req, $res) {
        
		$um = new RecaudoModel($req->getParsedBody());
        
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
    $this->delete('recaudos/{id}', function ($req, $res, $args) {
        
		$um = new RecaudoModel(null);
        $um->recaudo_id = $args["id"]; 

        //echo 'que: ' , $um->recaudo_id;
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->recaudo_id)
            )
        );
    });

    $this->delete('recaudos', function ($req, $res) {
        
		$um = new RecaudoModel(null);
        
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
    $this->post('recaudos/eliminarLote', function ($req, $res) {
        $um = new RecaudoModel(null);
        
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
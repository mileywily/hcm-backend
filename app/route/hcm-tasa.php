<?php

use App\Model\TasaModel;

$app->group('/api/', function () {
    
    $this->get('tasas/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tasas', function ($req, $res, $args) {
        
        $um = new TasaModel(null);
        
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
    $this->get('tasas/listar', function ($req, $res, $args) {

        $um = new TasaModel(null);
        
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
    $this->get('tasas/{id}', function ($req, $res, $args) {
        
		$um = new TasaModel(null);
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
    $this->post('tasas', function ($req, $res) {
         
		 $um = new TasaModel($req->getParsedBody());
                
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
    $this->put('tasas', function ($req, $res) {
        
		$um = new TasaModel($req->getParsedBody());
        
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
    $this->delete('tasas/{id}', function ($req, $res, $args) {
        
		$um = new TasaModel(null);
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

    $this->delete('tasas', function ($req, $res) {
        
		$um = new TasaModel(null);
        
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
    $this->post('tasas/eliminarLote', function ($req, $res) {
        $um = new TasaModel(null);
        
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
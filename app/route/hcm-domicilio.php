<?php

use App\Model\DomicilioModel;

$app->group('/api/', function () {
    
    $this->get('domicilios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('domicilios', function ($req, $res, $args) {
        
        $um = new DomicilioModel(null);
        
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
    $this->get('domicilios/listar', function ($req, $res, $args) {

        $um = new DomicilioModel(null);
        
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
    $this->get('domicilios/{id}', function ($req, $res, $args) {
        
		$um = new DomicilioModel(null);
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
    $this->post('domicilios', function ($req, $res) {
         
		 $um = new DomicilioModel($req->getParsedBody());
                
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
    $this->put('domicilios', function ($req, $res) {
        
		$um = new DomicilioModel($req->getParsedBody());
        
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
    $this->delete('domicilios/{id}', function ($req, $res, $args) {
        
		$um = new DomicilioModel(null);
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

    $this->delete('domicilios', function ($req, $res) {
        
		$um = new DomicilioModel(null);
        
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
    $this->post('domicilios/eliminarLote', function ($req, $res) {
        $um = new DomicilioModel(null);
        
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
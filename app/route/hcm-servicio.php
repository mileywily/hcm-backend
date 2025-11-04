<?php

use App\Model\ServicioModel;

$app->group('/api/', function () {
    
    $this->get('servicios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('servicios', function ($req, $res, $args) {
        
        $um = new ServicioModel(null);
        
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
    $this->get('servicios/listar', function ($req, $res, $args) {

        $um = new ServicioModel(null);
        
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
    $this->get('servicios/{id}', function ($req, $res, $args) {
        
		$um = new ServicioModel(null);
        $um->servicio_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->servicio_id)
            )
        );
    });



    //Insertar registro
    $this->post('servicios', function ($req, $res) {
         
		 $um = new ServicioModel($req->getParsedBody());
                
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
    $this->put('servicios', function ($req, $res) {
        
		$um = new ServicioModel($req->getParsedBody());
        
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
    $this->delete('servicios/{id}', function ($req, $res, $args) {
        
		$um = new ServicioModel(null);
        $um->servicio_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->servicio_id)
            )
        );
    });

    $this->delete('servicios', function ($req, $res) {
        
		$um = new ServicioModel(null);
        
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
    $this->post('servicios/eliminarLote', function ($req, $res) {
        $um = new ServicioModel(null);
        
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
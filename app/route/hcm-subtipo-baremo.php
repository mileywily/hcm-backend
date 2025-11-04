<?php

use App\Model\SubtipoBaremoModel;

$app->group('/api/', function () {
    
    $this->get('subtipobaremos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('subtipobaremos', function ($req, $res, $args) {
        
        $um = new SubtipoBaremoModel(null);
        
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
    $this->get('subtipobaremos/{id}', function ($req, $res, $args) {
        
		$um = new SubtipoBaremoModel(null);
        $um->subtipo_baremo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->subtipo_baremo_id)
            )
        );
    });

    //Insertar registro
    $this->post('subtipobaremos', function ($req, $res) {
         
		 $um = new SubtipoBaremoModel($req->getParsedBody());
                
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
    $this->put('subtipobaremos', function ($req, $res) {
        
		$um = new SubtipoBaremoModel($req->getParsedBody());
        
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
    $this->delete('subtipobaremos/{id}', function ($req, $res, $args) {
        
		$um = new SubtipoBaremoModel(null);
        $um->subtipo_baremo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->subtipo_baremo_id)
            )
        );
    });

    $this->delete('subtipobaremos', function ($req, $res) {
        
		$um = new SubtipoBaremoModel(null);
        
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
    $this->post('subtipobaremos/eliminarLote', function ($req, $res) {
        $um = new SubtipoBaremoModel(null);
        
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
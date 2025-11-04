<?php

use App\Model\TipoBaremoModel;

$app->group('/api/', function () {
    
    $this->get('tipobaremos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipobaremos', function ($req, $res, $args) {
        
        $um = new TipoBaremoModel(null);
        
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
    $this->get('tipobaremos/{id}', function ($req, $res, $args) {
        
		$um = new TipoBaremoModel(null);
        $um->tipo_baremo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_baremo_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipobaremos', function ($req, $res) {
         
		 $um = new TipoBaremoModel($req->getParsedBody());
                
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
    $this->put('tipobaremos', function ($req, $res) {
        
		$um = new TipoBaremoModel($req->getParsedBody());
        
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
    $this->delete('tipobaremos/{id}', function ($req, $res, $args) {
        
		$um = new TipoBaremoModel(null);
        $um->tipo_baremo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_baremo_id)
            )
        );
    });

    $this->delete('tipobaremos', function ($req, $res) {
        
		$um = new TipoBaremoModel(null);
        
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
    $this->post('tipobaremos/eliminarLote', function ($req, $res) {
        $um = new TipoBaremoModel(null);
        
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
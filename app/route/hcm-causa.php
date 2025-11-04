<?php

use App\Model\CausaModel;

$app->group('/api/', function () {
    
    $this->get('causas/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });

    //Obtener todos los registros para lista en combos
    $this->get('causas/listar', function ($req, $res, $args) {

        $um = new CausaModel(null);
        
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
    $this->get('causas', function ($req, $res, $args) {
        
        $um = new CausaModel(null);
        
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
    $this->get('causas/{id}', function ($req, $res, $args) {
        
		$um = new CausaModel(null);
        $um->causa_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->causa_id)
            )
        );
    });

    //Insertar registro
    $this->post('causas', function ($req, $res) {
         
		 $um = new CausaModel($req->getParsedBody());
                
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
    $this->put('causas', function ($req, $res) {
        
		$um = new CausaModel($req->getParsedBody());
        
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
    $this->delete('causas/{id}', function ($req, $res, $args) {
        
		$um = new CausaModel(null);
        $um->causa_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->causa_id)
            )
        );
    });

    $this->delete('causas', function ($req, $res) {
        
		$um = new CausaModel(null);
        
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
    $this->post('causas/eliminarLote', function ($req, $res) {
        $um = new CausaModel(null);
        
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
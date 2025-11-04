<?php

use App\Model\DocumentoModel;

$app->group('/api/', function () {
    
    $this->get('documentos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('documentos', function ($req, $res, $args) {
        
        $um = new DocumentoModel(null);
        
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
    $this->get('documentos/{id}', function ($req, $res, $args) {
        
		$um = new DocumentoModel(null);
        $um->documento_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->documento_id)
            )
        );
    });

    //Insertar registro
    $this->post('documentos', function ($req, $res) {
         
		 $um = new DocumentoModel($req->getParsedBody());
                
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
    $this->put('documentos', function ($req, $res) {
        
		$um = new DocumentoModel($req->getParsedBody());
        
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
    $this->delete('documentos/{id}', function ($req, $res, $args) {
        
		$um = new DocumentoModel(null);
        $um->documento_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->documento_id)
            )
        );
    });

    $this->delete('documentos', function ($req, $res) {
        
		$um = new DocumentoModel(null);
        
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
    $this->post('documentos/eliminarLote', function ($req, $res) {
        $um = new DocumentoModel(null);
        
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
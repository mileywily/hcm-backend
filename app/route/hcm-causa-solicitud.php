<?php

use App\Model\CausaSolicitudModel;

$app->group('/api/', function () {
    
    $this->get('causasolicitudes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });

    //Obtener todos los registros para lista en combos
    $this->get('causasolicitudes/listar', function ($req, $res, $args) {

        $um = new CausaSolicitudModel(null);
        
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
    $this->get('causasolicitudes', function ($req, $res, $args) {
        
        $um = new CausaSolicitudModel(null);
        
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
    $this->get('causasolicitudes/{id}', function ($req, $res, $args) {
        
		$um = new CausaSolicitudModel(null);
        $um->causa_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->causa_solicitud_id)
            )
        );
    });

    //Insertar registro
    $this->post('causasolicitudes', function ($req, $res) {
         
		 $um = new CausaSolicitudModel($req->getParsedBody());
                
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
    $this->put('causasolicitudes', function ($req, $res) {
        
		$um = new CausaSolicitudModel($req->getParsedBody());
        
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
    $this->delete('causasolicitudes/{id}', function ($req, $res, $args) {
        
		$um = new CausaSolicitudModel(null);
        $um->causa_solicitud_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->causa_solicitud_id)
            )
        );
    });

    $this->delete('causasolicitudes', function ($req, $res) {
        
		$um = new CausaSolicitudModel(null);
        
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
    $this->post('causasolicitudes/eliminarLote', function ($req, $res) {
        $um = new CausaSolicitudModel(null);
        
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
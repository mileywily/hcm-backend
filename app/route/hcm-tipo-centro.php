<?php

use App\Model\TipoCentroModel;

$app->group('/api/', function () {
    
    $this->get('tipocentro/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipocentro', function ($req, $res, $args) {
        
        $um = new TipoCentroModel(null);
        
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
    $this->get('tipocentro/listar', function ($req, $res, $args) {

        $um = new TipoCentroModel(null);
        
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
    $this->get('tipocentro/{id}', function ($req, $res, $args) {
        
		$um = new TipoCentroModel(null);
        $um->tipo_centro_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_centro_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipocentro', function ($req, $res) {
         
		 $um = new TipoCentroModel($req->getParsedBody());
                
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
    $this->put('tipocentro', function ($req, $res) {
        
		$um = new TipoCentroModel($req->getParsedBody());
        
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
    $this->delete('tipocentro/{id}', function ($req, $res, $args) {
        
		$um = new TipoCentroModel(null);
        $um->tipo_centro_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_centro_id)
            )
        );
    });

    $this->delete('tipocentro', function ($req, $res) {
        
		$um = new TipoCentroModel(null);
        
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
    $this->post('tipocentro/eliminarLote', function ($req, $res) {
        $um = new TipoCentroModel(null);
        
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
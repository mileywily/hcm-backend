<?php

use App\Model\EspecialidadModel;

$app->group('/api/', function () {
    
    $this->get('especialidades/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('especialidades', function ($req, $res, $args) {
        
        $um = new EspecialidadModel(null);
        
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
    $this->get('especialidades/listar', function ($req, $res, $args) {

        $um = new EspecialidadModel(null);
        
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
    $this->get('especialidades/{id}', function ($req, $res, $args) {
        
		$um = new EspecialidadModel(null);
        $um->especialidad_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->especialidad_id)
            )
        );
    });

    //Insertar registro
    $this->post('especialidades', function ($req, $res) {
         
		 $um = new EspecialidadModel($req->getParsedBody());
                
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
    $this->put('especialidades', function ($req, $res) {
        
		$um = new EspecialidadModel($req->getParsedBody());
        
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
    $this->delete('especialidades/{id}', function ($req, $res, $args) {
        
		$um = new EspecialidadModel(null);
        $um->especialidad_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->especialidad_id)
            )
        );
    });

    $this->delete('especialidades', function ($req, $res) {
        
		$um = new EspecialidadModel(null);
        
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
    $this->post('especialidades/eliminarLote', function ($req, $res) {
        $um = new EspecialidadModel(null);
        
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
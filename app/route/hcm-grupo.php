<?php

use App\Model\GrupoModel;

$app->group('/api/', function () {
    
    $this->get('grupos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('grupos', function ($req, $res, $args) {
        
        $um = new GrupoModel(null);
        
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
    $this->get('grupos/listar', function ($req, $res, $args) {

        $um = new GrupoModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Obtener todos los registros de especialidades del médico
    $this->get('grupos/listarportipo/{id}', function ($req, $res, $args) {

        $um = new GrupoModel(null);
        $um->tipo_grupo_id =  $args['id'];
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getGruposByTipo()
            )
        );
    });

    //Obtener registro por id
    $this->get('grupos/{id}', function ($req, $res, $args) {
        
		$um = new GrupoModel(null);
        $um->grupo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->grupo_id)
            )
        );
    });

    //Insertar registro
    $this->post('grupos', function ($req, $res) {
         
		 $um = new GrupoModel($req->getParsedBody());
                
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
    $this->put('grupos', function ($req, $res) {
        
		$um = new GrupoModel($req->getParsedBody());
        
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
    $this->delete('grupos/{id}', function ($req, $res, $args) {
        
		$um = new GrupoModel(null);
        $um->grupo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->grupo_id)
            )
        );
    });

    $this->delete('grupos', function ($req, $res) {
        
		$um = new GrupoModel(null);
        
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
    $this->post('grupos/eliminarLote', function ($req, $res) {
        $um = new GrupoModel(null);
        
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
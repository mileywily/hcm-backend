<?php

use App\Model\GrupoExamenModel;

$app->group('/api/', function () {
    
    $this->get('grupoexamenes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('grupoexamenes', function ($req, $res, $args) {
        
        $um = new GrupoExamenModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    //Servicios por grupo
    $this->get('grupoexamenes/grupo/{id}', function ($req, $res, $args) {
            
        $um = new GrupoExamenModel(null);
        $um->grupo_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByGrupo()
            )
        );
    });

    //Obtener todos los registros para lista en combos
    $this->get('grupoexamenes/listar', function ($req, $res, $args) {

        $um = new GrupoExamenModel(null);
        
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
    $this->get('grupoexamenes/{id}', function ($req, $res, $args) {
        
		$um = new GrupoExamenModel(null);
        $um->grupo_examen_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->grupo_examen_id)
            )
        );
    });

    //Insertar registro
    $this->post('grupoexamenes', function ($req, $res) {
         
		 $um = new GrupoExamenModel($req->getParsedBody());
                
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
    $this->put('grupoexamenes', function ($req, $res) {
        
		$um = new GrupoExamenModel($req->getParsedBody());
        
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
    $this->delete('grupoexamenes/{id}', function ($req, $res, $args) {
        
		$um = new GrupoExamenModel(null);
        $um->grupo_examen_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->grupo_examen_id)
            )
        );
    });

    $this->delete('grupoexamenes', function ($req, $res) {
        
		$um = new GrupoExamenModel(null);
        
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
    $this->post('grupoexamenes/eliminarLote', function ($req, $res) {
        $um = new GrupoExamenModel(null);
        
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
<?php

use App\Model\GrupoServicioModel;

$app->group('/api/', function () {
    
    $this->get('gruposervicios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('gruposervicios', function ($req, $res, $args) {
        
        $um = new GrupoServicioModel(null);
        
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
    $this->get('gruposervicios/grupo/{id}', function ($req, $res, $args) {
            
        $um = new GrupoServicioModel(null);
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
    $this->get('gruposervicios/listar', function ($req, $res, $args) {

        $um = new GrupoServicioModel(null);
        
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
    $this->get('gruposervicios/{id}', function ($req, $res, $args) {
        
		$um = new GrupoServicioModel(null);
        $um->grupo_servicio_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->grupo_servicio_id)
            )
        );
    });

    //Insertar registro
    $this->post('gruposervicios', function ($req, $res) {
         
		 $um = new GrupoServicioModel($req->getParsedBody());
                
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
    $this->put('gruposervicios', function ($req, $res) {
        
		$um = new GrupoServicioModel($req->getParsedBody());
        
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
    $this->delete('gruposervicios/{id}', function ($req, $res, $args) {
        
		$um = new GrupoServicioModel(null);
        $um->grupo_servicio_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->grupo_servicio_id)
            )
        );
    });

    $this->delete('gruposervicios', function ($req, $res) {
        
		$um = new GrupoServicioModel(null);
        
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
    $this->post('gruposervicios/eliminarLote', function ($req, $res) {
        $um = new GrupoServicioModel(null);
        
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
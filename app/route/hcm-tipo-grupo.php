<?php

use App\Model\TipoGrupoModel;

$app->group('/api/', function () {
    
    $this->get('tipogrupos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipogrupos', function ($req, $res, $args) {
        
        $um = new TipoGrupoModel(null);
        
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
    $this->get('tipogrupos/listar', function ($req, $res, $args) {

        $um = new TipoGrupoModel(null);
        
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
    $this->get('tipogrupos/{id}', function ($req, $res, $args) {
        
		$um = new TipoGrupoModel(null);
        $um->tipo_grupo_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_grupo_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipogrupos', function ($req, $res) {
         
		 $um = new TipoGrupoModel($req->getParsedBody());
                
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
    $this->put('tipogrupos', function ($req, $res) {
        
		$um = new TipoGrupoModel($req->getParsedBody());
        
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
    $this->delete('tipogrupos/{id}', function ($req, $res, $args) {
        
		$um = new TipoGrupoModel(null);
        $um->tipo_grupo_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_grupo_id)
            )
        );
    });

    $this->delete('tipogrupos', function ($req, $res) {
        
		$um = new TipoGrupoModel(null);
        
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
    $this->post('tipogrupos/eliminarLote', function ($req, $res) {
        $um = new TipoGrupoModel(null);
        
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
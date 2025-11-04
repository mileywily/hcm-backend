<?php

use App\Model\TipoConsultaModel;

$app->group('/api/', function () {
    
    $this->get('tipoconsultas/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipoconsultas', function ($req, $res, $args) {
        
        $um = new TipoConsultaModel(null);
        
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
    $this->get('tipoconsultas/listar', function ($req, $res, $args) {

        $um = new TipoConsultaModel(null);
        
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
    $this->get('tipoconsultas/{id}', function ($req, $res, $args) {
        
		$um = new TipoConsultaModel(null);
        $um->tipo_consulta_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_consulta_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipoconsultas', function ($req, $res) {
         
		 $um = new TipoConsultaModel($req->getParsedBody());
                
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
    $this->put('tipoconsultas', function ($req, $res) {
        
		$um = new TipoConsultaModel($req->getParsedBody());
        
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
    $this->delete('tipoconsultas/{id}', function ($req, $res, $args) {
        
		$um = new TipoConsultaModel(null);
        $um->tipo_consulta_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_consulta_id)
            )
        );
    });

    $this->delete('tipoconsultas', function ($req, $res) {
        
		$um = new TipoConsultaModel(null);
        
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
    $this->post('tipoconsultas/eliminarLote', function ($req, $res) {
        $um = new TipoConsultaModel(null);
        
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
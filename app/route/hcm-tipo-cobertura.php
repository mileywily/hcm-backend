<?php

use App\Model\TipoCoberturaModel;

$app->group('/api/', function () {
    
    $this->get('tipocoberturas/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipocoberturas', function ($req, $res, $args) {
        
        $um = new TipoCoberturaModel(null);
        
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
    $this->get('tipocoberturas/listar', function ($req, $res, $args) {

        $um = new TipoCoberturaModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Obtener todos los registros para lista en combos
    $this->get('tipocoberturas/listaractivas', function ($req, $res, $args) {

        $um = new TipoCoberturaModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllComboActivas()
            )
        );
    });

    //Obtener registro por id
    $this->get('tipocoberturas/{id}', function ($req, $res, $args) {
        
		$um = new TipoCoberturaModel(null);
        $um->tipo_cobertura_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_cobertura_id)
            )
        );
    });



    //Insertar registro
    $this->post('tipocoberturas', function ($req, $res) {
         
		 $um = new TipoCoberturaModel($req->getParsedBody());
                
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
    $this->put('tipocoberturas', function ($req, $res) {
        
		$um = new TipoCoberturaModel($req->getParsedBody());
        
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
    $this->delete('tipocoberturas/{id}', function ($req, $res, $args) {
        
		$um = new TipoCoberturaModel(null);
        $um->tipo_cobertura_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_cobertura_id)
            )
        );
    });

    $this->delete('tipocoberturas', function ($req, $res) {
        
		$um = new TipoCoberturaModel(null);
        
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
    $this->post('tipocoberturas/eliminarLote', function ($req, $res) {
        $um = new TipoCoberturaModel(null);
        
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
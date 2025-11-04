<?php

use App\Model\TipoDiagnosticoModel;

$app->group('/api/', function () {
    
    $this->get('tipodiagnosticos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipodiagnosticos', function ($req, $res, $args) {
        
        $um = new TipoDiagnosticoModel(null);
        
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
    $this->get('tipodiagnosticos/listar', function ($req, $res, $args) {

        $um = new TipoDiagnosticoModel(null);
        
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
    $this->get('tipodiagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new TipoDiagnosticoModel(null);
        $um->tipo_diagnostico_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_diagnostico_id)
            )
        );
    });
	
	
		  //Servicios por grupo
    $this->get('tipodiagnosticos/grupo/{id}', function ($req, $res, $args) {
            
        $um = new TipoDiagnosticoModel(null);
        $um->tipo_diagnostico_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByGrupo()
            )
        );
    });


    //Insertar registro
    $this->post('tipodiagnosticos', function ($req, $res) {
         
		 $um = new TipoDiagnosticoModel($req->getParsedBody());
                
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
    $this->put('tipodiagnosticos', function ($req, $res) {
        
		$um = new TipoDiagnosticoModel($req->getParsedBody());
        
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
    $this->delete('tipodiagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new TipoDiagnosticoModel(null);
        $um->tipo_diagnostico_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_diagnostico_id)
            )
        );
    });

    $this->delete('tipodiagnosticos', function ($req, $res) {
        
		$um = new TipoDiagnosticoModel(null);
        
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
    $this->post('tipodiagnosticos/eliminarLote', function ($req, $res) {
        $um = new TipoDiagnosticoModel(null);
        
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
<?php

use App\Model\GrupoDiagnosticoModel;

$app->group('/api/', function () {
    
    $this->get('grupodiagnosticos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('grupodiagnosticos', function ($req, $res, $args) {
        
        $um = new GrupoDiagnosticoModel(null);
        
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
    $this->get('grupodiagnosticos/listar', function ($req, $res, $args) {

        $um = new GrupoDiagnosticoModel(null);
        
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
    $this->get('grupodiagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new GrupoDiagnosticoModel(null);
        $um->grupo_diagnostico_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->grupo_diagnostico_id)
            )
        );
    });

    //Insertar registro
    $this->post('grupodiagnosticos', function ($req, $res) {
         
		 $um = new GrupoDiagnosticoModel($req->getParsedBody());
                
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
    $this->put('grupodiagnosticos', function ($req, $res) {
        
		$um = new GrupoDiagnosticoModel($req->getParsedBody());
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->update()
            )
        );
    });
	
	
	  //Servicios por grupo
    $this->get('grupodiagnosticos/grupo/{id}', function ($req, $res, $args) {
            
        $um = new GrupoDiagnosticoModel(null);
        $um->grupo_diagnostico_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByGrupo()
            )
        );
    });

    
    //Eliminar registro por id
    //
    $this->delete('grupodiagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new GrupoDiagnosticoModel(null);
        $um->grupo_diagnostico_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->grupo_diagnostico_id)
            )
        );
    });

    $this->delete('grupodiagnosticos', function ($req, $res) {
        
		$um = new GrupoDiagnosticoModel(null);
        
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
    $this->post('grupodiagnosticos/eliminarLote', function ($req, $res) {
        $um = new GrupoDiagnosticoModel(null);
        
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
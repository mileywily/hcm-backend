<?php

use App\Model\DiagnosticoModel;

$app->group('/api/', function () {
    
    $this->get('diagnosticos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('diagnosticos', function ($req, $res, $args) {
        
        $um = new DiagnosticoModel(null);
        
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
    $this->get('diagnosticos/listar', function ($req, $res, $args) {

        $um = new DiagnosticoModel(null);
        
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
    $this->get('diagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new DiagnosticoModel(null);
        $um->empresa_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->empresa_id)
            )
        );
    });

    //Insertar registro
    $this->post('diagnosticos', function ($req, $res) {
         
		 $um = new DiagnosticoModel($req->getParsedBody());
                
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
    $this->put('diagnosticos', function ($req, $res) {
        
		$um = new DiagnosticoModel($req->getParsedBody());
        
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
    $this->delete('diagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new DiagnosticoModel(null);
        $um->diagnostico_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->diagnostico_id)
            )
        );
    });

    $this->delete('diagnosticos', function ($req, $res) {
        
		$um = new DiagnosticoModel(null);
        
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
    $this->post('diagnosticos/eliminarLote', function ($req, $res) {
        $um = new DiagnosticoModel(null);
        
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
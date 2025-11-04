<?php

use App\Model\MedicoModel;

$app->group('/api/', function () {
    
    $this->get('medicos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('medicos', function ($req, $res, $args) {
        
        $um = new MedicoModel(null);
        
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
    $this->get('medicos/listar', function ($req, $res, $args) {

        $um = new MedicoModel(null);
        
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
    $this->get('medicos/{id}', function ($req, $res, $args) {
        
		$um = new MedicoModel(null);
        $um->medico_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->medico_id)
            )
        );
    });

    //Insertar registro
    $this->post('medicos', function ($req, $res) {
         
		 $um = new MedicoModel($req->getParsedBody());
                
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
    $this->put('medicos', function ($req, $res) {
        
		$um = new MedicoModel($req->getParsedBody());
        
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
    $this->delete('medicos/{id}', function ($req, $res, $args) {
        
		$um = new MedicoModel(null);
        $um->medico_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->medico_id)
            )
        );
    });

    $this->delete('medicos', function ($req, $res) {
        
		$um = new MedicoModel(null);
        
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
    $this->post('medicos/eliminarLote', function ($req, $res) {
        $um = new MedicoModel(null);
        
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
<?php

use App\Model\TipoLaboratorioModel;

$app->group('/api/', function () {
    
    $this->get('tipolaboratorios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipolaboratorios', function ($req, $res, $args) {
        
        $um = new TipoLaboratorioModel(null);
        
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
    $this->get('tipolaboratorios/listar', function ($req, $res, $args) {

        $um = new TipoLaboratorioModel(null);
        
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
    $this->get('tipolaboratorios/{id}', function ($req, $res, $args) {
        
		$um = new TipoLaboratorioModel(null);
        $um->tipo_laboratorio_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_laboratorio_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipolaboratorios', function ($req, $res) {
         
		 $um = new TipoLaboratorioModel($req->getParsedBody());
                
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
    $this->put('tipolaboratorios', function ($req, $res) {
        
		$um = new TipoLaboratorioModel($req->getParsedBody());
        
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
    $this->delete('tipolaboratorios/{id}', function ($req, $res, $args) {
        
		$um = new TipoLaboratorioModel(null);
        $um->tipo_laboratorio_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_laboratorio_id)
            )
        );
    });

    $this->delete('tipolaboratorios', function ($req, $res) {
        
		$um = new TipoLaboratorioModel(null);
        
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
    $this->post('tipolaboratorios/eliminarLote', function ($req, $res) {
        $um = new TipoLaboratorioModel(null);
        
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
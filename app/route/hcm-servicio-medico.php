<?php

use App\Model\ServicioMedicoModel;

$app->group('/api/', function () {
    
    $this->get('serviciomedicos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('serviciomedicos', function ($req, $res, $args) {
        
        $um = new ServicioMedicoModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    //Servicios del médico
    $this->get('serviciomedicos/medico/{id}', function ($req, $res, $args) {
            
        $um = new ServicioMedicoModel(null);
        $um->medico_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByMedico()
            )
        );
    });


    //Obtener todos los registros para lista en combos
    $this->get('serviciomedicos/listar', function ($req, $res, $args) {

        $um = new ServicioMedicoModel(null);
        
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
    $this->get('serviciomedicos/{id}', function ($req, $res, $args) {
        
		$um = new ServicioMedicoModel(null);
        $um->servicio_medico_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->servicio_medico_id)
            )
        );
    });

    //Insertar registro
    $this->post('serviciomedicos', function ($req, $res) {
         
		 $um = new ServicioMedicoModel($req->getParsedBody());
                
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
    $this->put('serviciomedicos', function ($req, $res) {
        
		$um = new ServicioMedicoModel($req->getParsedBody());
        
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
    $this->delete('serviciomedicos/{id}', function ($req, $res, $args) {
        
		$um = new ServicioMedicoModel(null);
        $um->servicio_medico_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->servicio_medico_id)
            )
        );
    });

    $this->delete('serviciomedicos', function ($req, $res) {
        
		$um = new ServicioMedicoModel(null);
        
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
    $this->post('serviciomedicos/eliminarLote', function ($req, $res) {
        $um = new ServicioMedicoModel(null);
        
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
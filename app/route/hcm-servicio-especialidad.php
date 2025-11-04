<?php

use App\Model\ServicioEspecialidadModel;

$app->group('/api/', function () {
    
    $this->get('servicioespecialidades/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('servicioespecialidades', function ($req, $res, $args) {
        
        $um = new ServicioEspecialidadModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    //Servicios por especialidad
    $this->get('servicioespecialidades/especialidad/{id}', function ($req, $res, $args) {
            
        $um = new ServicioEspecialidadModel(null);
        $um->especialidad_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByEspecialidad()
            )
        );
    });

    

    //Servicios automáticos por especialidad
    $this->get('servicioespecialidades/estandard/{id}', function ($req, $res, $args) {
        
        $um = new ServicioEspecialidadModel(null);
        $um->especialidad_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getEstandardByEspecialidad()
            )
        );
    });


    //Obtener todos los registros para lista en combos
    $this->get('servicioespecialidades/listar', function ($req, $res, $args) {

        $um = new ServicioEspecialidadModel(null);
        
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
    $this->get('servicioespecialidades/{id}', function ($req, $res, $args) {
        
		$um = new ServicioEspecialidadModel(null);
        $um->servicio_especialidad_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->servicio_especialidad_id)
            )
        );
    });

    //Servicios por especialidad
    $this->get('servicioespecialidades/especialidadtipo/{id}/{tipo}', function ($req, $res, $args) {
        
        $um = new ServicioEspecialidadModel(null);
        $um->especialidad_id =  $args['id'];
        $um->tipo_atencion_id =  $args['tipo'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByEspecialidadTipo()
            )
        );
    });

    //Insertar registro
    $this->post('servicioespecialidades', function ($req, $res) {
         
		 $um = new ServicioEspecialidadModel($req->getParsedBody());
                
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
    $this->put('servicioespecialidades', function ($req, $res) {
        
		$um = new ServicioEspecialidadModel($req->getParsedBody());
        
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
    $this->delete('servicioespecialidades/{id}', function ($req, $res, $args) {
        
		$um = new ServicioEspecialidadModel(null);
        $um->servicio_especialidad_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->servicio_especialidad_id)
            )
        );
    });

    $this->delete('servicioespecialidades', function ($req, $res) {
        
		$um = new ServicioEspecialidadModel(null);
        
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
    $this->post('servicioespecialidades/eliminarLote', function ($req, $res) {
        $um = new ServicioEspecialidadModel(null);
        
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
<?php

use App\Model\MedicoEspecialidadModel;

$app->group('/api/', function () {
    
    $this->get('medicoespecialidades/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('medicoespecialidades', function ($req, $res, $args) {
        
        $um = new MedicoEspecialidadModel(null);
        
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
    $this->get('medicoespecialidades/listar', function ($req, $res, $args) {

        $um = new MedicoEspecialidadModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Obtener todos los registros de especialidades del médico
    $this->get('medicoespecialidades/especialidades/{id}', function ($req, $res, $args) {

        $um = new MedicoEspecialidadModel(null);
        $um->medico_id =  $args['id'];
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getEspecialidaByMedico($um->medico_id)
            )
        );
    });

    //Obtener todos los médicos de una especialidad
    $this->get('medicoespecialidades/medicos/{id}', function ($req, $res, $args) {

        $um = new MedicoEspecialidadModel(null);
        $um->especialidad_id =  $args['id'];
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getMedicoByEspecialidad()
            )
        );
    });

    //Obtener registro por id
    $this->get('medicoespecialidades/{id}', function ($req, $res, $args) {
        
		$um = new MedicoEspecialidadModel(null);
        $um->medico_especialidad_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->medico_especialidad_id)
            )
        );
    });

    //Insertar registro
    $this->post('medicoespecialidades', function ($req, $res) {
         
		 $um = new MedicoEspecialidadModel($req->getParsedBody());
                
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
    $this->put('medicoespecialidades', function ($req, $res) {
        
		$um = new MedicoEspecialidadModel($req->getParsedBody());
        
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
    $this->delete('medicoespecialidades/{id}', function ($req, $res, $args) {
        
		$um = new MedicoEspecialidadModel(null);
        $um->medico_especialidad_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->medico_especialidad_id)
            )
        );
    });

    $this->delete('medicoespecialidades', function ($req, $res) {
        
		$um = new MedicoEspecialidadModel(null);
        
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
    $this->post('medicoespecialidades/eliminarLote', function ($req, $res) {
        $um = new MedicoEspecialidadModel(null);
        
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
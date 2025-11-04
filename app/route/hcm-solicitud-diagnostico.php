<?php

use App\Model\SolicitudDiagnosticoModel;

$app->group('/api/', function () {
    
    $this->get('solicitudiagnosticos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('solicitudiagnosticos', function ($req, $res, $args) {
        
        $um = new SolicitudDiagnosticoModel(null);
        
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
    $this->get('solicitudiagnosticos/listar', function ($req, $res, $args) {

        $um = new SolicitudDiagnosticoModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

   //Examenes asociados a la solicitud en la relacion grupo_examen (Examenes solicitados por Solicitud)
    $this->get('solicitudiagnosticos/grupoexamen/solicitud/{id}', function ($req, $res, $args) {

        $um = new SolicitudDiagnosticoModel(null);
        $um->solicitud_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getDiagnosticosBySolicitud()
            )
        );
    });

    //Examenes soliictados y asignados por solicitud
    $this->get('solicitudiagnosticos/asignados/solicitud/{id}', function ($req, $res, $args) {

    $um = new SolicitudDiagnosticoModel(null);
    $um->solicitud_id =  $args['id'];

    return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
        json_encode(
            $um->getDiagnosticosAsignadosBySolicitud()
        )
    );
});

    //Obtener registro por id
    $this->get('solicitudiagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudDiagnosticoModel(null);
        $um->solicitud_diagnostico_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->solicitud_diagnostico_id)
            )
        );
    });

    //Insertar registro
    $this->post('solicitudiagnosticos', function ($req, $res) {
         
		 $um = new SolicitudDiagnosticoModel($req->getParsedBody());
                
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
    $this->put('solicitudiagnosticos', function ($req, $res) {
        
		$um = new SolicitudDiagnosticoModel($req->getParsedBody());
        
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
    $this->delete('solicitudiagnosticos/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudDiagnosticoModel(null);
        $um->solicitud_diagnostico_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->solicitud_diagnostico_id)
            )
        );
    });

    $this->delete('solicitudiagnosticos', function ($req, $res) {
        
		$um = new SolicitudDiagnosticoModel(null);
        
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
    $this->post('solicitudiagnosticos/eliminarLote', function ($req, $res) {
        $um = new SolicitudDiagnosticoModel(null);
        
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
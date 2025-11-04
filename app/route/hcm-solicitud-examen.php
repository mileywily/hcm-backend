<?php

use App\Model\SolicitudExamenModel;

$app->group('/api/', function () {
    
    $this->get('solicitudexamenes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('solicitudexamenes', function ($req, $res, $args) {
        
        $um = new SolicitudExamenModel(null);
        
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
    $this->get('solicitudexamenes/listar', function ($req, $res, $args) {

        $um = new SolicitudExamenModel(null);
        
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
    $this->get('solicitudexamenes/grupoexamen/solicitud/{id}', function ($req, $res, $args) {

        $um = new SolicitudExamenModel(null);
        $um->solicitud_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getExamenesBySolicitud()
            )
        );
    });

    //Examenes soliictados y asignados por solicitud
    $this->get('solicitudexamenes/asignados/solicitud/{id}', function ($req, $res, $args) {

    $um = new SolicitudExamenModel(null);
    $um->solicitud_id =  $args['id'];

    return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
        json_encode(
            $um->getExamenesAsignadosBySolicitud()
        )
    );
});

    //Obtener registro por id
    $this->get('solicitudexamenes/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudExamenModel(null);
        $um->solicitud_examen_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->solicitud_examen_id)
            )
        );
    });

    //Insertar registro
    $this->post('solicitudexamenes', function ($req, $res) {
         
		 $um = new SolicitudExamenModel($req->getParsedBody());
                
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
    $this->put('solicitudexamenes', function ($req, $res) {
        
		$um = new SolicitudExamenModel($req->getParsedBody());
        
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
    $this->delete('solicitudexamenes/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudExamenModel(null);
        $um->solicitud_examen_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->solicitud_examen_id)
            )
        );
    });

    $this->delete('solicitudexamenes', function ($req, $res) {
        
		$um = new SolicitudExamenModel(null);
        
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
    $this->post('solicitudexamenes/eliminarLote', function ($req, $res) {
        $um = new SolicitudExamenModel(null);
        
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
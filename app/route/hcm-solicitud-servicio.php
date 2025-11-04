<?php

use App\Model\SolicitudServicioModel;

$app->group('/api/', function () {
    
    $this->get('solicitudservicios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('solicitudservicios', function ($req, $res, $args) {
        
        $um = new SolicitudServicioModel(null);
        
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
    $this->get('solicitudservicios/listar', function ($req, $res, $args) {

        $um = new SolicitudServicioModel(null);
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllCombo()
            )
        );
    });

    //Servicios asociados a la solicitud en la relacion servicio_tipoatencion
    $this->get('solicitudservicios/solicitud/{id}', function ($req, $res, $args) {
        
        $um = new SolicitudServicioModel(null);
        $um->solicitud_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllBySolicitud()
            )
        );
    });

    //Servicios asociados a la solicitud en la relacion grupo_servicio
    $this->get('solicitudservicios/gruposervicio/solicitud/{id}', function ($req, $res, $args) {
    
        $um = new SolicitudServicioModel(null);
        $um->solicitud_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getGrupoServicioBySolicitud()
            )
        );
    });

    //Servicios asociados a la solicitud sin el servicio de consulta
    $this->get('solicitudservicios/estandards/{id}', function ($req, $res, $args) {
    
        $um = new SolicitudServicioModel(null);
        $um->solicitud_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllAutomaticosBySolicitud()
            )
        );
    });

    //Obtener registro por id
    $this->get('solicitudservicios/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudServicioModel(null);
        $um->solicitud_servicio_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->solicitud_servicio_id)
            )
        );
    });

    //Insertar registro
    $this->post('solicitudservicios', function ($req, $res) {
         
		 $um = new SolicitudServicioModel($req->getParsedBody());
                
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
    $this->put('solicitudservicios', function ($req, $res) {
        
		$um = new SolicitudServicioModel($req->getParsedBody());
        
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
    $this->delete('solicitudservicios/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudServicioModel(null);
        $um->solicitud_servicio_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->solicitud_servicio_id)
            )
        );
    });

    $this->delete('solicitudservicios', function ($req, $res) {
        
		$um = new SolicitudServicioModel(null);
        
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
    $this->post('solicitudservicios/eliminarLote', function ($req, $res) {
        $um = new SolicitudServicioModel(null);
        
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
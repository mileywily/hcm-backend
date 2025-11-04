<?php

use App\Model\SolicitudPresupuestoModel;

$app->group('/api/', function () {
    
    $this->get('solicitudpresupuestos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('solicitudpresupuestos', function ($req, $res, $args) {
        
        $um = new SolicitudPresupuestoModel(null);
        
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
    $this->get('solicitudpresupuestos/listar', function ($req, $res, $args) {

        $um = new SolicitudPresupuestoModel(null);
        
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
    $this->get('solicitudpresupuestos/solicitud/{id}', function ($req, $res, $args) {
        
        $um = new SolicitudPresupuestoModel(null);
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
    $this->get('solicitudpresupuestos/gruposervicio/solicitud/{id}', function ($req, $res, $args) {
    
        $um = new SolicitudPresupuestoModel(null);
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
    $this->get('solicitudpresupuestos/estandards/{id}', function ($req, $res, $args) {
    
        $um = new SolicitudPresupuestoModel(null);
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
    $this->get('solicitudpresupuestos/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudPresupuestoModel(null);
        $um->solicitud_presupuesto_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->solicitud_presupuesto_id)
            )
        );
    });

    //Insertar registro
    $this->post('solicitudpresupuestos', function ($req, $res) {
         
		 $um = new SolicitudPresupuestoModel($req->getParsedBody());
                
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
    $this->put('solicitudpresupuestos', function ($req, $res) {
        
		$um = new SolicitudPresupuestoModel($req->getParsedBody());
        
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
    $this->delete('solicitudpresupuestos/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudPresupuestoModel(null);
        $um->solicitud_presupuesto_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->solicitud_presupuesto_id)
            )
        );
    });

    $this->delete('solicitudpresupuestos', function ($req, $res) {
        
		$um = new SolicitudPresupuestoModel(null);
        
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
    $this->post('solicitudpresupuestos/eliminarLote', function ($req, $res) {
        $um = new SolicitudPresupuestoModel(null);
        
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
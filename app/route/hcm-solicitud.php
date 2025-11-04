<?php

use App\Model\SolicitudModel;

$app->group('/api/', function () {
    
    $this->get('solicitudes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('solicitudes', function ($req, $res, $args) {
        
        $um = new SolicitudModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    
    //Obtener registro por id
    $this->get('solicitudes/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudModel(null);
        $um->solicitud_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->solicitud_id)
            )
        );
    });

    //cancelar solicitudes
    $this->post('solicitudes/cancelarsolicitud', function ($req, $res) {

        $um = new SolicitudModel($req->getParsedBody());
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->cancelarSolicitud()
            )
        );
    });
	
	
	    //enviar solicitudes
    $this->post('solicitudes/enviarsolicitud', function ($req, $res) {

        $um = new SolicitudModel($req->getParsedBody());
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->enviarSolicitud()
            )
        );
    });

    //Consultar solicitudes por beneficiario
    $this->post('solicitudes/beneficiario', function ($req, $res) {
        $um = new SolicitudModel($req->getParsedBody());

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getSolicitudesByBeneficiario()
            )
        );
    });


    //Consultar solicitudes por beneficiario
    $this->post('solicitudes/beneficiarioactivas', function ($req, $res) {
        $um = new SolicitudModel($req->getParsedBody());

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getSolicitudesAbiertasByBeneficiario()
            )
        );
    });

    //Consultar solicitudes de un titular y su carga familiar
    $this->post('solicitudes/titular', function ($req, $res) {
        $um = new SolicitudModel($req->getParsedBody());

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getSolicitudesByTitular()
            )
        );
    });

        //Consultar el total de solicitudes de un titular y su carga familiar por mes 
        $this->post('solicitudes/titularCantidad', function ($req, $res) 
        {
            $um = new SolicitudModel($req->getParsedBody());
    
            return $res
                ->withHeader('Content-type', 'application/json')
                ->getBody()
                ->write(
                json_encode(
                    $um->getSolicitudesByTitularCantidad()
                )
            );
        });
	

    //Insertar registro
    $this->post('solicitudes', function ($req, $res) {
         
		 $um = new SolicitudModel($req->getParsedBody());


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
    $this->put('solicitudes', function ($req, $res) {
        
		$um = new SolicitudModel($req->getParsedBody());
        
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
    $this->delete('solicitudes/{id}', function ($req, $res, $args) {
        
		$um = new SolicitudModel(null);
        $um->solicitud_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->solicitud_id)
            )
        );
    });

    $this->delete('solicitudes', function ($req, $res) {
        
		$um = new SolicitudModel(null);
        
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
    $this->post('solicitudes/eliminarLote', function ($req, $res) {
        $um = new SolicitudModel(null);
        
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
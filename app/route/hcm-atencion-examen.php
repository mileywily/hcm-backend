<?php

use App\Model\AtencionExamenModel;

$app->group('/api/', function () {
    
    $this->get('atencionexamenes/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('atencionexamenes', function ($req, $res, $args) {
        
        $um = new AtencionExamenModel(null);
        
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
    $this->get('atencionexamenes/listar', function ($req, $res, $args) {

        $um = new AtencionExamenModel(null);
        
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
    $this->get('atencionexamenes/{id}', function ($req, $res, $args) {
        
		$um = new AtencionExamenModel(null);
        $um->atencion_examen_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->atencion_examen_id)
            )
        );
    });

    //Examenes asociados a la solicitud en la relacion grupo_servicio
    $this->get('atencionexamenes/grupoexamen/atencion/{id}', function ($req, $res, $args) {

        $um = new AtencionExamenModel(null);
        $um->atencion_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getExamenesByAtencion()
            )
        );
    });

    //Insertar registro
    $this->post('atencionexamenes', function ($req, $res) {
         
		 $um = new AtencionExamenModel($req->getParsedBody());
                
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
    $this->put('atencionexamenes', function ($req, $res) {
        
		$um = new AtencionExamenModel($req->getParsedBody());
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->update()
            )
        );
    });

    //Actualizar en lote
    $this->post('atencionexamenes/updatelote', function ($req, $res) {
         
        $um = new AtencionExamenModel(null);
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->updateLote($req->getParsedBody())
            )
        );
   });
    
    //Eliminar registro por id
    //
    $this->delete('atencionexamenes/{id}', function ($req, $res, $args) {
        
		$um = new AtencionExamenModel(null);
        $um->atencion_examen_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->atencion_examen_id)
            )
        );
    });

    $this->delete('atencionexamenes', function ($req, $res) {
        
		$um = new AtencionExamenModel(null);
        
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
    $this->post('atencionexamenes/eliminarLote', function ($req, $res) {
        $um = new AtencionExamenModel(null);
        
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
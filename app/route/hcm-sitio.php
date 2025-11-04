<?php

use App\Model\SitioModel;

$app->group('/api/', function () {
    
    $this->get('sitios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('sitios', function ($req, $res, $args) {
        
        $um = new SitioModel(null);
        
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
    $this->get('sitios/listar', function ($req, $res, $args) {

        $um = new SitioModel(null);
        
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
    $this->get('sitios/{id}', function ($req, $res, $args) {
        
		$um = new SitioModel(null);
        $um->sitio_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->sitio_id)
            )
        );
    });

    //Insertar registro
    $this->post('sitios', function ($req, $res) {
         
		 $um = new SitioModel($req->getParsedBody());
                
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
    $this->put('sitios', function ($req, $res) {
        
		$um = new SitioModel($req->getParsedBody());
        
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
    $this->delete('sitios/{id}', function ($req, $res, $args) {
        
		$um = new SitioModel(null);
        $um->sitio_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->sitio_id)
            )
        );
    });

    $this->delete('sitios', function ($req, $res) {
        
		$um = new SitioModel(null);
        
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
    $this->post('sitios/eliminarLote', function ($req, $res) {
        $um = new SitioModel(null);
        
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
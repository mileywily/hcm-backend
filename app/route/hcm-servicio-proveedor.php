<?php

use App\Model\ServicioProveedorModel;

$app->group('/api/', function () {
    
    $this->get('servicioproveedores/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('servicioproveedores', function ($req, $res, $args) {
        
        $um = new ServicioProveedorModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });
	
	  $this->get('servicioproveedores/id_prov/{id}', function ($req, $res, $args) {
        $um = new ServicioProveedorModel(null);
        $um->proveedor_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getAllByProveedor($um->proveedor_id)
            )
        );
    });
	
	
	  //Servicios del médico
    $this->get('servicioproveedores/proveedor/{id}', function ($req, $res, $args) {
            
        $um = new ServicioProveedorModel(null);
        $um->proveedor_id =  $args['id'];

        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getAllByProvedor()
            )
        );
    });


    //Obtener registro por id
    $this->get('servicioproveedores/{id}', function ($req, $res, $args) {
        
		$um = new ServicioProveedorModel(null);
        $um->servicio_proveedor_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->servicio_proveedor_id)
            )
        );
    });

    //Insertar registro
    $this->post('servicioproveedores', function ($req, $res) {
         
		 $um = new ServicioProveedorModel($req->getParsedBody());
                
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
    $this->put('servicioproveedores', function ($req, $res) {
        
		$um = new ServicioProveedorModel($req->getParsedBody());
        
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
    $this->delete('servicioproveedores/{id}', function ($req, $res, $args) {
        
		$um = new ServicioProveedorModel(null);
        $um->servicio_proveedor_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->servicio_proveedor_id)
            )
        );
    });

    $this->delete('servicioproveedores', function ($req, $res) {
        
		$um = new ServicioProveedorModel(null);
        
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
    $this->post('servicioproveedores/eliminarLote', function ($req, $res) {
        $um = new ServicioProveedorModel(null);
        
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
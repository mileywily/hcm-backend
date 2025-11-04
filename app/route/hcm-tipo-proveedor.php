<?php

use App\Model\TipoProveedorModel;

$app->group('/api/', function () {
    
    $this->get('tipoproveedores/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('tipoproveedores', function ($req, $res, $args) {
        
        $um = new TipoProveedorModel(null);
        
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
    $this->get('tipoproveedores/listar', function ($req, $res, $args) {

        $um = new TipoProveedorModel(null);
        
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
    $this->get('tipoproveedores/{id}', function ($req, $res, $args) {
        
		$um = new TipoProveedorModel(null);
        $um->tipo_proveedor_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->tipo_proveedor_id)
            )
        );
    });

    //Insertar registro
    $this->post('tipoproveedores', function ($req, $res) {
         
		 $um = new TipoProveedorModel($req->getParsedBody());
                
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
    $this->put('tipoproveedores', function ($req, $res) {
        
		$um = new TipoProveedorModel($req->getParsedBody());
        
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
    $this->delete('tipoproveedores/{id}', function ($req, $res, $args) {
        
		$um = new TipoProveedorModel(null);
        $um->tipo_proveedor_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->tipo_proveedor_id)
            )
        );
    });

    $this->delete('tipoproveedores', function ($req, $res) {
        
		$um = new TipoProveedorModel(null);
        
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
    $this->post('tipoproveedores/eliminarLote', function ($req, $res) {
        $um = new TipoProveedorModel(null);
        
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
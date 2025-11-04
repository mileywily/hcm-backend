<?php

use App\Model\EmpresaModel;

$app->group('/api/', function () {
    
    $this->get('empresas/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('empresas', function ($req, $res, $args) {
        
        $um = new EmpresaModel(null);
        
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
    $this->get('empresas/{id}', function ($req, $res, $args) {
        
		$um = new EmpresaModel(null);
        $um->empresa_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->empresa_id)
            )
        );
    });

    //Insertar registro
    $this->post('empresas', function ($req, $res) {
         
		 $um = new EmpresaModel($req->getParsedBody());
                
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
    $this->put('empresas', function ($req, $res) {
        
		$um = new EmpresaModel($req->getParsedBody());
        
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
    $this->delete('empresas/{id}', function ($req, $res, $args) {
        
		$um = new EmpresaModel(null);
        $um->empresa_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->empresa_id)
            )
        );
    });

    $this->delete('empresas', function ($req, $res) {
        
		$um = new EmpresaModel(null);
        
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
    $this->post('empresas/eliminarLote', function ($req, $res) {
        $um = new EmpresaModel(null);
        
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
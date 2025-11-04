<?php
use App\Model\UsuarioModel;

$app->group('/api/', function () {
    
    $this->get('usuarios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Servicio de Unidad de Medida');
    });
    
    //Obtener todos los registros
    $this->get('usuarios', function ($req, $res, $args) {
        
        $um = new UsuarioModel(null);
        
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
    $this->get('usuarios/{id}', function ($req, $res, $args) {
        
	$um = new UsuarioModel(null);
        $um->usuario_id = $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->usuario_id)
            )
        );
    });


    //Insertar registro
    $this->post('usuarios', function ($req, $res) {
         
	$um = new UsuarioModel($req->getParsedBody());
                
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
    $this->put('usuarios', function ($req, $res) {
        
	$um = new UsuarioModel($req->getParsedBody());
        
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
    $this->delete('usuarios/{id}', function ($req, $res, $args) {
        
	$um = new UsuarioModel(null);
        $um->usuario_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->usuario_id)
            )
        );
    });

    $this->delete('usuarios', function ($req, $res) {
        
	$um = new UsuarioModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->deleteAll()
            )
        );
    }); 

    $this->post('usuario/login', function ($req, $res) {
        
		$um = new UsuarioModel($req->getParsedBody());
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Login()
            )
        );
    });
    
});
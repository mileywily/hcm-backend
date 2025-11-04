<?php

use App\Model\BeneficiarioModel;

$app->group('/api/', function () {
    
    $this->get('beneficiarios/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('beneficiarios', function ($req, $res, $args) {
        
        $um = new BeneficiarioModel(null);
        
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
    $this->get('beneficiarios/{id}', function ($req, $res, $args) {
        
		$um = new BeneficiarioModel(null);
        $um->beneficiario_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->beneficiario_id)
            )
        );
    });

    //Consultar beneficiarios por parametros
    $this->post('beneficiarios/consultar', function ($req, $res) {
        $um = new BeneficiarioModel($req->getParsedBody());
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getBeneficiarioByParametros()
            )
        );
    });

    //Consultar titular por beneficiario
    $this->post('beneficiarios/titular', function ($req, $res) {
        $um = new BeneficiarioModel($req->getParsedBody());
        
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->getTitularByBeneficiario()
            )
        );
    });

        //cancelar ordenes de servicio
    $this->post('beneficiarios/actualizarfromsiss', function ($req, $res) {

        $um = new BeneficiarioModel($req->getParsedBody());
                
        return $res
            ->withHeader('Content-type', 'application/json')
            ->getBody()
            ->write(
            json_encode(
                $um->updateCargaFamiliarFromSiss()
            )
        );
    });

    //Insertar registro
    $this->post('beneficiarios', function ($req, $res) {
         
		 $um = new BeneficiarioModel($req->getParsedBody());
                
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
    $this->put('beneficiarios', function ($req, $res) {
        
		$um = new BeneficiarioModel($req->getParsedBody());
        
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
    $this->delete('beneficiarios/{id}', function ($req, $res, $args) {
        
		$um = new BeneficiarioModel(null);
        $um->beneficiario_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->beneficiario_id)
            )
        );
    });

    $this->delete('beneficiarios', function ($req, $res) {
        
		$um = new BeneficiarioModel(null);
        
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
    $this->post('beneficiarios/eliminarLote', function ($req, $res) {
        $um = new BeneficiarioModel(null);
        
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
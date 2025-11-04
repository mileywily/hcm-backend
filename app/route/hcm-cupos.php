<?php

use App\Model\CuposModel;

$app->group('/api/', function () {
    
    $this->get('cupos/test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello');
    });
    
    //Obtener todos los registros
    $this->get('cupos', function ($req, $res, $args) {
        
        $um = new CuposModel(null);
        
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
    $this->get('cupos/{id}', function ($req, $res, $args) {
        
		$um = new CuposModel(null);
        $um->cupos_id =  $args['id'];
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->getById($um->cupos_id)
            )
        );
    });

    //Cupos del medico, según especialidad y turno
    $this->post('cupos/calendario', function ($req, $res) {
         
        $um = new CuposModel($req->getParsedBody());
               
       return $res
          ->withHeader('Content-type', 'application/json')
          ->getBody()
          ->write(
           json_encode(
               $um->getCuposByFilters()
           )
       );
   });

    //Cupos del medico, según especialidad y turno
    $this->post('cupos/calendario/triaje', function ($req, $res) {
         
        $um = new CuposModel($req->getParsedBody());
               
       return $res
          ->withHeader('Content-type', 'application/json')
          ->getBody()
          ->write(
           json_encode(
               $um->getCuposTriajeByFilters()
           )
       );
   });
   
   
      //Cupos del medico, según especialidad y turno
    $this->post('cupos/calendario/laboratorio', function ($req, $res) {
         
        $um = new CuposModel($req->getParsedBody());
               
       return $res
          ->withHeader('Content-type', 'application/json')
          ->getBody()
          ->write(
           json_encode(
               $um->getCuposLaboratorioByFilters()
           )
       );
   });
   
    //Insertar registros por lotes.  Recibe un array
    $this->post('cupos/lotes', function ($req, $res) {
         
        $um = new CuposModel($req->getParsedBody());
               
       return $res
          ->withHeader('Content-type', 'application/json')
          ->getBody()
          ->write(
           json_encode(
                $um->createByLote(
                   $req->getParsedBody()
                )
           )
       );
   });
   
       //Insertar actualizar por lotes.  Recibe un array
    $this->post('cupos/updatebylotes', function ($req, $res) {
         
        $um = new CuposModel($req->getParsedBody());
               
       return $res
          ->withHeader('Content-type', 'application/json')
          ->getBody()
          ->write(
           json_encode(
                $um->updateByLote(
                   $req->getParsedBody()
                )
           )
       );
   });

    //Insertar registro
    $this->post('cupos', function ($req, $res) {
         
		 $um = new CuposModel($req->getParsedBody());
                
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
    $this->put('cupos', function ($req, $res) {
        
		$um = new CuposModel($req->getParsedBody());
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->update()
            )
        );
    });
    
    //Eliminar registros por rango, medico y especialidad 
    $this->delete('cupos/eliminarRango', function ($req, $res) {
    
        $um = new CuposModel($req->getParsedBody());

        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->deleteByRange(
                    $req->getParsedBody()
                )
            )
        );
    });

    //Eliminar registros por rango, medico y especialidad 
    $this->delete('cupos/eliminarRangoTriaje', function ($req, $res) {

        $um = new CuposModel($req->getParsedBody());

        return $res
        ->withHeader('Content-type', 'application/json')
        ->getBody()
        ->write(
            json_encode(
                $um->deleteByRangeTriaje(
                    $req->getParsedBody()
                )
            )
        );
    });
    
    //Eliminar registro por id
    //
    $this->delete('cupos/{id}', function ($req, $res, $args) {
        
		$um = new CuposModel(null);
        $um->cupos_id = $args["id"]; 

        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->delete($um->cupos_id)
            )
        );
    });

    $this->delete('cupos', function ($req, $res) {
        
		$um = new CuposModel(null);
        
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
    $this->post('cupos/eliminarLote', function ($req, $res) {
        $um = new CuposModel(null);
        
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
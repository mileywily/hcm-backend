<?php
use App\Model\TurnoModel;

$app->group('/api/', function () {
    

    //Obtener todos los registros
    $this->get('turnos', function ($req, $res, $args) {
        
        $um = new TurnoModel(null);
        
        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });


    
});
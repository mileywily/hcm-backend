<?php

namespace App\Model;

use App\Lib\ListaCodigoMensaje;
use PDOException;

use App\Lib\Crud;

class CuposModel extends Crud
{

  /* campos de la tabla */
  public  $cupo_id;
  private $sitio_id;
  public  $medico_id;
  public  $especialidad_id;
  public  $servicio_id;
  private $fecha;
  private $cupos;
  private $cupos_asignados;
  private $is_disponible;
  public  $turno;
  public  $horario;
  
  public $asignar;

  
  private $created;
  private $usuario_creador;
  private $nombre_creador;
  private $modified;
  private $usuario_modificador;
  private $nombre_modificador;

  private $tipo_atencion_id;


  const TABLE 		= 'cupos'; 	// Nombre de la tabla fisica
  const IDNAMETABLE = 'cupo_id'; // nombre del id de la tabla fisica
  
   
	public function __construct($data)
	{
		parent::__construct(self::TABLE, self::IDNAMETABLE);

		$this->cupo_id	          = $data["cupo_id"];
		$this->sitio_id 	        = $data["sitio_id"];
		$this->medico_id 	        = $data["medico_id"];
    $this->especialidad_id 	  = $data["especialidad_id"];
    $this->turno_id 	        = $data["turno_id"];
    $this->servicio_id 	      = $data["servicio_id"]; 
    $this->grupo_id 	        = $data["grupo_id"]; 
    $this->proveedor_id 	    = $data["proveedor_id"]; 

    $this->fecha 	            = $data["fecha"];
    $this->horario 	          = $data["horario"];
    $this->cupos 	            = $data["cupos"];
    $this->cupos_asignados  	= $data["cupos_asignados"];
    $this->tipo_atencion_id  	= $data["tipo_atencion_id"];

    $this->is_disponible 	    = $data["is_disponible"];

    $this->asignar 	          = $data["asignar"];

    $this->usuario_creador    = $data["usuario_creador"];
    $this->nombre_creador     = $data["nombre_creador"];
    $this->modified           = $data["modified"];
    $this->usuario_modificador= $data["usuario_modificador"];
    $this->nombre_modificador = $data["nombre_modificador"];


	}
  
	public function create(){
		try{
            $this->pdo = parent::conexion();
			$sql = "INSERT INTO $this->table (
                                          sitio_id,
                                          medico_id,
                                          especialidad_id,
                                          turno_id,
                                          fecha,
                                          horario,
                                          cupos,
                                          cupos_asignados,
                                          is_disponible,
                                          servicio_id,
                                          usuario_creador,
                                          nombre_creador,
                                          usuario_modificador,
                                          nombre_modificador,

                                          grupo_id,
                                          proveedor_id,
                                          tipo_atencion_id
                                        ) 
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(      
                        $this->sitio_id, 	
                        $this->medico_id, 	
                        $this->especialidad_id, 
                        $this->turno_id, 	
                        $this->fecha, 	       
                        $this->horario, 	       
                        $this->cupos, 	       
                        $this->cupos_asignados,                        
                        $this->is_disponible,
                        $this->servicio_id,
                        $this->usuario_creador,
                        $this->nombre_creador,
                        $this->usuario_modificador,
                        $this->nombre_modificador,

                        $this->grupo_id,
                        $this->proveedor_id,
                        $this->tipo_atencion_id

            ));
			$this->pdo = null;

	     	$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se ha creado correctamente el registro", ListaCodigoMensaje::$COD_AGREGAR_REG );
         	return $this->response;
		 
	   }catch(PDOException $e){
		
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
			return $this->response;
	   }
	}

 public function insertCupoLote($sitio_id,$medico_id,$especialidad_id,$turno_id,$horario,$cupos, $fecha,$servicio_id,$usuario_creador,$nombre_creador,$usuario_modificador,$nombre_modificador,$tipo_atencion_id,$grupo_id, $proveedor_id,$cupos_asignados)
 {
	  $this->pdo = parent::conexion();

      $sql = "INSERT INTO $this->table (
          sitio_id,
          medico_id,
          especialidad_id,
          turno_id,
          horario,
          cupos,
          fecha,
          servicio_id,
          usuario_creador,
          nombre_creador,
          usuario_modificador,
          nombre_modificador,

          tipo_atencion_id,
          grupo_id,
          proveedor_id,
		  cupos_asignados

        ) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

      $stm = $this->pdo->prepare($sql);
	  $stm->execute(array(      
          $sitio_id,$medico_id,$especialidad_id,$turno_id,$horario,$cupos, $fecha,$servicio_id,$usuario_creador,$nombre_creador,$usuario_modificador,$nombre_modificador,$tipo_atencion_id,$grupo_id, $proveedor_id,$cupos_asignados
        ));
 }

  public function createByLote($data){

    try{

      $this->pdo = parent::conexion();
      $datos = $data['datos'];      
      $fechas = $data['rangoFechas'];

      foreach($fechas as $fecha) {
		  
		  
        $valor=0;
		$valor = $this->cantidad_cupos($datos['medico_id'],$datos['especialidad_id'],$datos['sitio_id'],$datos['turno_id'],$datos['servicio_id'],$datos['grupo_id'],$datos['proveedor_id'],$datos['tipo_atencion_id'],$fecha);
		
		if ($valor ==0)
		{
           
			$this->insertCupoLote($datos['sitio_id'],$datos['medico_id'],$datos['especialidad_id'],$datos['turno_id'],$datos['horario'], $datos['cupos'], $fecha, $datos['servicio_id'], $datos['usuario_creador'], $datos['nombre_creador'], $datos['usuario_modificador'],			  $datos['nombre_modificador'],
			  $datos['tipo_atencion_id'],$datos['grupo_id'],$datos['proveedor_id'],0);
			         $this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se han actualizados correctamente el/los cupo/s", ListaCodigoMensaje::$COD_AGREGAR_REG );
    
		}
		else
		{
			$consulta=$this->update_CuposLotes($datos['medico_id'],$datos['especialidad_id'],$datos['sitio_id'],$datos['turno_id'],$datos['servicio_id'],$datos['grupo_id'],$datos['proveedor_id'],$datos['tipo_atencion_id'],$fecha,$datos['cupos']);
		//	 $this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $consulta, ListaCodigoMensaje::$COD_AGREGAR_REG );
		   
	        $this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se han creado correctamente el/los cupo/s", ListaCodigoMensaje::$COD_AGREGAR_REG );
           
    	}
		
		
      }

      $this->pdo = null;

       return $this->response;
	   


    }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
		return $this->response;
			
	  }

  }
  
  
  
  public function updateByLote($data){

    try{

      $this->pdo = parent::conexion();
      $datos = $data['datos'];      
      $fechas = $data['rangoFechas'];

      foreach($fechas as $fecha) {
		  
		  
        $valor=0;
		$valor = $this->cantidad_cupos($datos['medico_id'],$datos['especialidad_id'],$datos['sitio_id'],$datos['turno_id_new'],$datos['servicio_id'],$datos['grupo_id'],$datos['proveedor_id'],$datos['tipo_atencion_id'],$fecha);
		
		if ($valor ==0)
		{
           
			$this->insertCupoLote($datos['sitio_id'],$datos['medico_id'],$datos['especialidad_id'],$datos['turno_id_new'],$datos['horario'], $datos['cupos'], $fecha, $datos['servicio_id'], $datos['usuario_creador'], $datos['nombre_creador'], $datos['usuario_modificador'],			  $datos['nombre_modificador'],
			  $datos['tipo_atencion_id'],$datos['grupo_id'],$datos['proveedor_id'],$datos['cupos_asignados']);
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se han actualizados correctamente el/los cupo/s", ListaCodigoMensaje::$COD_AGREGAR_REG );
    		

	
		}
		else
		{
			$consulta=$this->update_CuposAsignadosLotes($datos['medico_id'],$datos['especialidad_id'],$datos['sitio_id'],$datos['turno_id_new'],$datos['servicio_id'],$datos['grupo_id'],$datos['proveedor_id'],$datos['tipo_atencion_id'],$fecha,$datos['cupos_asignados'],$datos['cupos']);
		   
	        $this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, "Se han actualizado  correctamente el/los cupo/s", ListaCodigoMensaje::$COD_AGREGAR_REG );
           
    	}
		
			$sql2 = " update atencion set turno_id  = ?  where medico_id = ? AND servicio_id = ?  AND especialidad_id = ?  AND tipo_atencion_id = ?   AND fecha_cita = ?  AND turno_id = ? ";
		    $stm = $this->pdo->prepare($sql2);
		    $stm->execute(array(
		    $datos['turno_id_new'],
		    $datos['medico_id'],
	         $datos['servicio_id'],
		    $datos['especialidad_id'],
		    $datos['tipo_atencion_id'],
		    $fecha,
			$datos['turno_id']));
		
		
			$sql = " delete from  cupos  where medico_id = ? AND servicio_id = ?  AND especialidad_id = ?  AND tipo_atencion_id = ?   AND fecha = ?  AND turno_id = ?";
			$stm = $this->pdo->prepare($sql);
			$stm->execute(array(
			$datos['medico_id'],
			$datos['servicio_id'],
			$datos['especialidad_id'],
			$datos['tipo_atencion_id'],
			$fecha,$datos['turno_id']));

      }

      $this->pdo = null;

       return $this->response;
	   


    }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_AGREGAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR );
		return $this->response;
			
	  }

  }
  
   public function cantidad_cupos($medico_id,$especialidad_id,$sitio_id,$turno_id,$servicio_id,$grupo_id,$proveedor_id,$tipo_atencion_id,$fecha)
	{
	     try
		 {
     	   if (($tipo_atencion_id ==2) or ($tipo_atencion_id ==5))
			{
			   $sql = " SELECT * FROM cupos where sitio_id = ? 	AND	 medico_id = ? AND especialidad_id = ?  AND turno_id = ? AND servicio_id = ? AND tipo_atencion_id= ? and fecha =?   ";
			   $stm = $this->pdo->prepare($sql);
			   $stm->execute(array($sitio_id, $medico_id,$especialidad_id, $turno_id,$servicio_id,$tipo_atencion_id,$fecha));
			}
			else
			{
					if ($tipo_atencion_id == 3) 
					{
						 $sql = " SELECT * FROM cupos where  turno_id = ? AND  tipo_atencion_id = ? AND fecha = ?  AND grupo_id = ? AND proveedor_id = ?  ";
				         $stm = $this->pdo->prepare($sql);
			             $stm->execute(array($turno_id,$tipo_atencion_id,$fecha,$grupo_id,$proveedor_id));
              	    }
					else
					{
						 $sql = " SELECT * FROM cupos where turno_id = ? AND servicio_id = ? AND tipo_atencion_id = ? AND fecha = ?   AND proveedor_id = ?  ";
		                 $stm = $this->pdo->prepare($sql);
			             $stm->execute(array( $turno_id,$servicio_id,$tipo_atencion_id,$fecha,$proveedor_id));
		            }
			}
			$registros =  $stm->fetchAll();
		 }
		 catch(PDOException $e){
            $registros = 0;
		 }
			return count($registros);
		
	}
	
	   public function update_CuposLotes($medico_id,$especialidad_id,$sitio_id,$turno_id,$servicio_id,$grupo_id,$proveedor_id,$tipo_atencion_id,$fecha,$cupos)
	{
	     try
		 {
     	   if (($tipo_atencion_id ==2) or ($tipo_atencion_id ==5))
			{
			   $sql = " Update  cupos set cupos = cupos + ? where sitio_id = ? 	AND	 medico_id = ? AND especialidad_id = ?  AND turno_id = ? AND servicio_id = ? AND tipo_atencion_id= ? and fecha =?   ";
			   $stm = $this->pdo->prepare($sql);
			   $stm->execute(array($cupos,$sitio_id, $medico_id,$especialidad_id, $turno_id,$servicio_id,$tipo_atencion_id,$fecha));
			}
			else
			{
					if ($tipo_atencion_id == 3) 
					{
						 $sql = " Update  cupos set cupos = cupos + ? where  turno_id = ?  AND tipo_atencion_id = ? AND fecha = ?  AND grupo_id = ? AND proveedor_id = ?  ";
				         
						 $stm = $this->pdo->prepare($sql);
			             $stm->execute(array($cupos,$turno_id,$tipo_atencion_id,$fecha,$grupo_id,$proveedor_id));
						// echo $sql ;
              	    }
					else
					{
						 $sql = " Update  cupos set cupos = cupos + ? where  turno_id = ? AND servicio_id = ? AND tipo_atencion_id = ? AND fecha = ?   AND proveedor_id = ?  ";
		                 $stm = $this->pdo->prepare($sql);
			             $stm->execute(array($cupos,$turno_id,$servicio_id,$tipo_atencion_id,$fecha,$proveedor_id));
		            }
			}
			$registros = $sql; //$stm->fetchAll();
		 }
		 catch(PDOException $e){
            $registros = -20;
		 }
			return count($registros);
		
	}
	
	public function update_CuposAsignadosLotes($medico_id,$especialidad_id,$sitio_id,$turno_id,$servicio_id,$grupo_id,$proveedor_id,$tipo_atencion_id,$fecha,$cuposasignados,$cupos)
	{
	     try
		 {
     	   if (($tipo_atencion_id ==2) or ($tipo_atencion_id ==5))
			{
			   $sql = " Update  cupos set cupos_asignados = cupos_asignados + ?,cupos = cupos + ? where sitio_id = ? 	AND	 medico_id = ? AND especialidad_id = ?  AND turno_id = ? AND servicio_id = ? AND tipo_atencion_id= ? and fecha =?   ";
			   $stm = $this->pdo->prepare($sql);
			   $stm->execute(array($cuposasignados,$cupos,$sitio_id, $medico_id,$especialidad_id, $turno_id,$servicio_id,$tipo_atencion_id,$fecha));
			}
			else
			{
					if ($tipo_atencion_id == 3) 
					{
						 $sql = " Update  cupos set cupos_asignados = cupos_asignados + ?,cupos = cupos + ? where  turno_id = ?  AND tipo_atencion_id = ? AND fecha = ?  AND grupo_id = ? AND proveedor_id = ?  ";
				         
						 $stm = $this->pdo->prepare($sql);
			             $stm->execute(array($cuposasignados,$cupos,$turno_id,$tipo_atencion_id,$fecha,$grupo_id,$proveedor_id));
						// echo $sql ;
              	    }
					else
					{
						 $sql = " Update  cupos set cupos_asignados = cupos_asignados + ?,cupos = cupos + ?  where  turno_id = ? AND servicio_id = ? AND tipo_atencion_id = ? AND fecha = ?   AND proveedor_id = ?  ";
		                 $stm = $this->pdo->prepare($sql);
			             $stm->execute(array($cuposasignados,$cupos,$turno_id,$servicio_id,$tipo_atencion_id,$fecha,$proveedor_id));
		            }
			}
			$registros = $sql; //$stm->fetchAll();
		 }
		 catch(PDOException $e){
            $registros = -20;
		 }
			return count($registros);
		
	}


	public function update(){
		try{

      $this->pdo = parent::conexion();
		 
      $time = date("Y-m-d H:i:s", time());

      $sql = "
        UPDATE $this->table SET 
            sitio_id=?, 
            medico_id=?, 
            especialidad_id=?, 
            turno_id=?, 
            horario=?, 
            cupos=?,
            cupos_asignados=?,
            servicio_id=?,
            usuario_creador=?,
            nombre_creador=?,
            usuario_modificador=?,
            nombre_modificador=?, 

            grupo_id=?, 
            proveedor_id=?, 

            modified=now()
        WHERE $this->idTableName = ?";


      $stm = $this->pdo->prepare($sql);

      $stm->execute(array(
              $this->sitio_id, 	
              $this->medico_id, 	
              $this->especialidad_id, 
              $this->turno_id, 	       
              $this->horario, 	       
              $this->cupos,
              $this->cupos_asignados,
              $this->servicio_id,
              $this->usuario_creador,
              $this->nombre_creador,
              $this->usuario_modificador,
              $this->nombre_modificador,
              $this->grupo_id,
              $this->proveedor_id,
              $this->cupo_id
      ));
      
      $this->pdo = null;

      $this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, "Se ha modificado correctamente el registro", ListaCodigoMensaje::$COD_MODIFICAR_REG );
      return $this->response;

	  }catch(PDOException $e){
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
	  }
	}

  //Filtros: medico_id, especialidad_id, turno_id
  public function getCuposByFilters(){
    try
    {
      $this->pdo = parent::conexion();
        
      $sql = "
        Select 
              m.*, c.* , e.*,
              c.fecha as date,       
              concat(t.nombre,'- C:', c.cupos - coalesce(c.cupos_asignados,0) ) as title,
              t.turno_id,
              e.nombre as especialidad,
              s.nombre sitio,
              CASE WHEN c.cupos=coalesce(c.cupos_asignados,0) THEN false ELSE true END  is_disponible,
              CASE WHEN c.cupos = coalesce(c.cupos_asignados,0) THEN 'red' ELSE 'green'END  color,
              DATE_FORMAT(c.fecha , '%d/%m/%Y') as fecha
      
        FROM cupos c
        inner join medico m ON m.medico_id = c.medico_id
        inner join especialidad e ON e.especialidad_id = c.especialidad_id
        inner join turno t ON t.turno_id = c.turno_id
        inner join sitio s ON s.sitio_id = c.sitio_id
        
        where 
          c.medico_id = ? and servicio_id = ? and tipo_atencion_id = ?
      ";      

      $datos = array($this->medico_id, $this->servicio_id, $this->tipo_atencion_id);

      if($this->especialidad_id ){
        $sql.= "AND c.especialidad_id = ?";
        array_push($datos , $this->especialidad_id);
      }

      if($this->turno_id ){
        $sql.= "AND c.turno_id = ?";
        array_push($datos , $this->turno_id);
      }
      
      if($this->asignar){
        $sql.=" and c.fecha >= current_date; ";
      }

      $stm = $this->pdo->prepare($sql);

      $stm->execute($datos);
        
      $this->pdo = null;

      $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);

      $this->response->result = $stm->fetchAll();

      return $this->response->result;

    }
    catch (PDOException $e)
    {
      $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
      return $this->response;
    }
  }



    //Filtros: medico_id, especialidad_id, turno_id
    public function getCuposTriajeByFilters(){
      try
      {
        $this->pdo = parent::conexion();
		
		if (is_numeric($this->servicio_id) )
		{
          
        $sql = "
          Select 
                g.*, c.*,".$this->servicio_id ."  as servicio_id, 
                c.fecha as date,       
                concat(t.nombre,'- C:', c.cupos - c.cupos_asignados ) as title,
                t.turno_id,
                p.nombre proveedor,
                CASE WHEN c.cupos=c.cupos_asignados THEN false ELSE true END  is_disponible,
                CASE WHEN c.cupos = c.cupos_asignados THEN 'red' ELSE 'green'END  color,
                DATE_FORMAT(c.fecha , '%d/%m/%Y') as fecha
        
          FROM cupos c
          left join grupo g ON g.grupo_id = c.grupo_id
          inner join turno t ON t.turno_id = c.turno_id
          inner join proveedor p ON p.proveedor_id = c.proveedor_id
          
          where 
                c.grupo_id = ? and c.proveedor_id = ? and tipo_atencion_id = ?
        ";      
		
		}
		else
		{
			
			        $sql = "
          Select 
                g.*, c.*,c.fecha as date,       
                concat(t.nombre,'- C:', c.cupos - c.cupos_asignados ) as title,
                t.turno_id,
                p.nombre proveedor,
                CASE WHEN c.cupos=c.cupos_asignados THEN false ELSE true END  is_disponible,
                CASE WHEN c.cupos = c.cupos_asignados THEN 'red' ELSE 'green'END  color,
                DATE_FORMAT(c.fecha , '%d/%m/%Y') as fecha
        
          FROM cupos c
          left join grupo g ON g.grupo_id = c.grupo_id
          inner join turno t ON t.turno_id = c.turno_id
          inner join proveedor p ON p.proveedor_id = c.proveedor_id
          
          where 
                c.grupo_id = ? and c.proveedor_id = ? and tipo_atencion_id = ?
        ";  
		}
  
        $datos = array($this->grupo_id, $this->proveedor_id, $this->tipo_atencion_id);
  
/*
       if($this->grupo_id ){
          $sql.= "AND c.grupo_id = ?";
          array_push($datos , $this->grupo_id);
        }*/
  
        if($this->turno_id ){
          $sql.= "AND c.turno_id = ?";
          array_push($datos , $this->turno_id);
        }
        
        if($this->asignar){
          $sql.=" and c.fecha >= current_date; ";
        }
  

        //echo $sql;
        $stm = $this->pdo->prepare($sql);
  
        $stm->execute($datos);
          
        $this->pdo = null;
  
        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);
  
        $this->response->result = $stm->fetchAll();
  
        return $this->response->result;
  
      }
      catch (PDOException $e)
      {
        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
        return $this->response;
      }
    }
	
	
	    //Filtros: medico_id, especialidad_id, turno_id
    public function getCuposLaboratorioByFilters(){
      try
      {
        $this->pdo = parent::conexion();
          
        $sql = "
          Select 
                g.*, c.*,
                c.fecha as date,       
                concat(t.nombre,'- C:', c.cupos - c.cupos_asignados ) as title,
                t.turno_id,
                p.nombre proveedor,
                CASE WHEN c.cupos=c.cupos_asignados THEN false ELSE true END  is_disponible,
                CASE WHEN c.cupos = c.cupos_asignados THEN 'red' ELSE 'green'END  color,
                DATE_FORMAT(c.fecha , '%d/%m/%Y') as fecha
        
          FROM cupos c
          left join grupo g ON g.grupo_id = c.grupo_id
          inner join turno t ON t.turno_id = c.turno_id
          inner join proveedor p ON p.proveedor_id = c.proveedor_id
          
          where 
                servicio_id = ? and c.proveedor_id = ? and tipo_atencion_id = ?
        ";      
  
        $datos = array($this->servicio_id, $this->proveedor_id, $this->tipo_atencion_id);
  

       if($this->grupo_id ){
          $sql.= "AND c.grupo_id = ?";
          array_push($datos , $this->grupo_id);
        }
  
        if($this->turno_id ){
          $sql.= "AND c.turno_id = ?";
          array_push($datos , $this->turno_id);
        }
        
        if($this->asignar){
          $sql.=" and c.fecha >= current_date; ";
        }
  

        //echo $sql;
        $stm = $this->pdo->prepare($sql);
  
        $stm->execute($datos);
          
        $this->pdo = null;
  
        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, "Consulta de registros exitosa", ListaCodigoMensaje::$COD_LEER_TODOS_REG);
  
        $this->response->result = $stm->fetchAll();
  
        return $this->response->result;
  
      }
      catch (PDOException $e)
      {
        $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
        return $this->response;
      }
    }

  
  //Elimina registros dado  medico_id, especialidad_id y rango de fecha
	public function deleteByRange($data)
	{
		try
		{
	  $fecha_inicio = "$data[fecha_inicio]";
      $fecha_fin = "$data[fecha_fin]";

      $this->pdo = parent::conexion();
	  
	  $fecha_i= strtotime($fecha_inicio);
	  $fecha_f= strtotime($fecha_fin);
	  $dia = 86400;
	  
	  while($fecha_i <=  $fecha_f){
	  
	           $fecha =  date("Y-m-d", $fecha_i);
	  
				$sql1 = " select count(*) 
				FROM atencion
				WHERE medico_id = ? and servicio_id = ? and especialidad_id = ? and tipo_atencion_id = ?
				AND (fecha_cita BETWEEN '$fecha' AND '$fecha' ) and estado_atencion_id =2 ";

      
			  $sql = " 
				FROM $this->table 
				WHERE medico_id = ? and servicio_id = ? and especialidad_id = ? and tipo_atencion_id = ?
				AND (fecha BETWEEN '$fecha' AND '$fecha' )
				AND cupos_asignados = 0
			  ";
	  
	

			  $params = array(
				$this->medico_id,
				$this->servicio_id,
				$this->especialidad_id,
				$this->tipo_atencion_id
			  );
	  


			  if($this->turno_id )
			  {
				$sql.= " AND turno_id = ? ";
				
				$sql1.= " AND turno_id = ? ";
				array_push($params  , $this->turno_id);
				
			  }
			  
			  $stm = $this->pdo->prepare(" $sql1 ");	
			  $stm->execute($params);
			  $registro = count($stm->fetchAll());
			  

			  //Validar que hayan registros que se puedan eliminar
			  $stm = $this->pdo->prepare(" SELECT * $sql ");	
			  $stm->execute($params);
			  $reg = $stm->fetchAll();


			  if (($reg)and (count($registro ==0)))
			  {

				$stm = $this->pdo->prepare(" DELETE $sql ");			          
				$stm->execute($params);
				// $message = "Se ha eliminado el/los registro/s que no tenian cupos asignados.";
				//$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $message , ListaCodigoMensaje::$COD_ELIMINAR_REG );

			  }else
			  {

				  $sql = " Update  cupos set cupos = cupos_asignados where medico_id = ? AND servicio_id = ?  AND especialidad_id = ?  AND tipo_atencion_id = ?   AND fecha = ? and turno_id = ? ";
				  
				  $stm = $this->pdo->prepare($sql);
				  $stm->execute(array(
				  $this->medico_id,
				  $this->servicio_id,
				  $this->especialidad_id,
				  $this->tipo_atencion_id,
				  $fecha,$this->turno_id));
			  }
	           $fecha_i += $dia;
		}

            $message = "Cupos Actualizados dentro de ese rango de fechas."; 
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $message , ListaCodigoMensaje::$COD_ELIMINAR_NO_CONTENT );    
			$this->pdo = null;
			return $this->response;
		}   
		catch(PDOException $e)
		{
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
		}
	}	


    //Elimina registros dado  medico_id, especialidad_id y rango de fecha
	public function deleteByRangeTriaje($data)
	{
		try
		{
			$fecha_inicio = "$data[fecha_inicio]";
            $fecha_fin = "$data[fecha_fin]";
			$fecha_i= strtotime($fecha_inicio);
	        $fecha_f= strtotime($fecha_fin);
	        $dia = 86400;

            $this->pdo = parent::conexion();
			
			 while($fecha_i <=  $fecha_f)
			 {
			        
	           $fecha =  date("Y-m-d", $fecha_i);
			   
			   
			   
				   $sql1 = " select count(*) 
						FROM atencion
						WHERE  proveedor_id = ? and tipo_atencion_id = ?
						AND (fecha_cita BETWEEN '$fecha' AND '$fecha' ) and estado_atencion_id = 2  ";

			  
			  
					$sql = " 
						FROM $this->table 
						WHERE proveedor_id = ? and tipo_atencion_id = ?
						AND (fecha BETWEEN '$fecha' AND '$fecha' )
					  ";
			
					  $params = array(
						$this->proveedor_id,
						$this->tipo_atencion_id
					  );
					  
 					  $stm = $this->pdo->prepare(" $sql1 ");	
					  $stm->execute($params);
					  $registro = count($stm->fetchAll());

					  if($this->grupo_id ){
						$sql.= " AND grupo_id = ? ";
						array_push($params  , $this->grupo_id);
					  }

					  if($this->turno_id ){
						$sql.= " AND turno_id = ? ";
						array_push($params  , $this->turno_id);
					  }
					  

					  
					if ($tipo_atencion_id != 3) 
					{
						 if($this->servicio_id ){
						$sql.= " AND servicio_id = ? ";
						array_push($params  , $this->servicio_id);
					  }
					}


					  //Validar que hayan registros que se puedan eliminar
					
					  $stm = $this->pdo->prepare(" SELECT * $sql ");	
					  $stm->execute($params);
					  $reg = $stm->fetchAll();
					   // echo "registro".count($reg);
					  


					  if ((count($reg ==0))and (count($registro ==0)))
					  {

						$stm = $this->pdo->prepare(" DELETE $sql ");			          
						$stm->execute($params);
						$message = "Se ha eliminado el/los registro/s que no tenian cupos asignados.";
					
						$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $message , ListaCodigoMensaje::$COD_ELIMINAR_REG );

					  }else{
						  
						 if ($tipo_atencion_id != 3) 
					     {
							  $sql = " Update  cupos set cupos = cupos_asignados where  servicio_id = ?  AND proveedor_id = ?  AND tipo_atencion_id = ?   AND fecha = ?  ";
							  
							  $stm = $this->pdo->prepare($sql);
							  $stm->execute(array(
							  $this->servicio_id,
							  $this->proveedor_id,
							  $this->tipo_atencion_id,
							  $fecha));
						 }
						 else
						 {
							   $sql = " Update  cupos set cupos = cupos_asignados where  grupo_id = ?  AND proveedor_id = ?  AND tipo_atencion_id = ?   AND fecha = ?  ";
							  
							  $stm = $this->pdo->prepare($sql);
							  $stm->execute(array(
							  $this->grupo_id,
							  $this->proveedor_id,
							  $this->tipo_atencion_id,
							  $fecha));
						 }

						$message = "No hay cupos registrados o que puedan ser eliminados dentro de ese rango de fechas."; 
						$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $message , ListaCodigoMensaje::$COD_ELIMINAR_NO_CONTENT );
					  }
					  $fecha_i += $dia;
			 }
           //  $message = "Cupos Actualizados dentro de ese rango de fechas."; 
            // $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $message , ListaCodigoMensaje::$COD_ELIMINAR_NO_CONTENT ); 
      $this->pdo = null;
			return $this->response;
		}   
		catch(PDOException $e)
		{
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
		}
	}	


	//Elimina varios regristro
	public function deleteByLote($data)
	{
		try
		{
			for ($i=0; $i < count($data) ; $i++) {
				$this->delete($data[$i]['cupos_id']);
			}
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, "Se han eliminado los registros satisfactoriamente", ListaCodigoMensaje::$COD_ELIMINAR_TODOS_REG );
			return $this->response;
		
		}   
		catch(PDOException $e)
		{
			$this->response->setResponse(ListaCodigoMensaje::$ACCION_MODIFICAR_REG, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
		}
	}
	
	public function DescontarCupo($id)
	{
        try
        {
            $this->pdo = parent::conexion();
            $sql = " UPDATE cupos set cupos_asignados =  cupos_asignados - 1 WHERE   cupo_id =  $id ";
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, "Se desconto un  cupo", ListaCodigoMensaje::$COD_ELIMINAR_TODOS_REG );
			return $this->response;

        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_POR_ID, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }
	
	public function AumentarCupo($id)
	{
        try
        {
            $this->pdo = parent::conexion();
            $sql = " UPDATE cupos set cupos_asignados =  cupos_asignados + 1 WHERE   cupo_id =  $id ";
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
            $this->pdo = null;

            $this->response->setResponse(ListaCodigoMensaje::$ACCION_ELIMINAR_TODOS_REG, "Se Aumento un  cupo", ListaCodigoMensaje::$COD_ELIMINAR_TODOS_REG );
			return $this->response;

        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_POR_ID, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }
	
	public function ObtenerCupo_Citas($atencion)
	{
        try
        {
            $this->pdo = parent::conexion();
            $sql = "SELECT *
							FROM hcm_db.cupos c
							
							WHERE   c.sitio_id         = $atencion->sitio_id AND 
									c.medico_id        = $atencion->medico_id AND 
									c.especialidad_id  = $atencion->especialidad_id AND 
									c.turno_id         = $atencion->turno_id AND 
									c.servicio_id      = $atencion->servicio_id AND 
									c.fecha            = SUBSTRING('$atencion->fecha_cita',1,10);                          
							";


							$stm = $this->pdo->prepare($sql);
							$stm->execute();

							
            $this->pdo = null;

            
			return $cupo =  $stm->fetch();

        }
        catch (PDOException $e)
        {
            $this->response->setResponse(ListaCodigoMensaje::$ACCION_LEER_POR_ID, $e->getMessage(), ListaCodigoMensaje::$COD_ERROR);
			return $this->response;
        }
    }
	
	

	
}
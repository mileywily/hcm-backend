<?php

namespace App\Lib;

class ListaCodigoMensaje
{
	public static $COD_AGREGAR_REG    = '201';
    public static $ACCION_AGREGAR_REG    = 'Ingresar.';

    public static $COD_MODIFICAR_REG  = "304";
    public static $ACCION_MODIFICAR_REG  = "Modificar.";

    public static $COD_ELIMINAR_REG   = "305";
    public static $ACCION_ELIMINAR_REG   = "Eliminar.";

    public static $COD_CANCELAR_REG   = "308";
    public static $ACCION_CANCELAR_REG   = "Cancelar.";

    public static $COD_ELIMINAR_TODOS_REG   = "306";
    public static $COD_ELIMINAR_NO_CONTENT   = "204"; //Petición aceptada pero sin cambios 
    public static $ACCION_ELIMINAR_TODOS_REG   = "Eliminar Todos.";

    
    public static $COD_LEER_TODOS_REG   = "307.";
    public static $ACCION_LEER_TODOS_REG   = "Consultar Todos.";
    public static $ACCION_LEER_POR_ID  = "Consultar por ID.";

    public static $COD_CREAR_ORDENES_TRIAJE   = "310.";
    public static $ACCION_CREAR_ORDENES_TRIAJE   = "Crear Ordenes.";
    public static $ACCION_CREAR_ORDENES_POR_TRIAJE  = "Creación de Ordenes de Servicio.";


    public static $COD_ERROR          = "500";
}
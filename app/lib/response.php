<?php
namespace App\Lib;

class Response
{
	public $accion 		= null;
	public $details    	= null;
	public $status     	= null;

	public function SetResponse($accion, $details, $status)
	{
		$this->accion  = $accion;
		$this->details = $details;
		$this->status  = $status;
	}
}

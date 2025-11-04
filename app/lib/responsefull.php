<?php
namespace App\Lib;

class Responsefull
{
	public $accion 		= null;
	public $details    	= null;
	public $status     	= null;
	public $result     	= null;

	public function SetResponse($accion, $details, $status, $result)
	{
		$this->accion  = $accion;
		$this->details = $details;
		$this->status  = $status;
		$this->result  = $result;
	}
}

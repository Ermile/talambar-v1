<?php
namespace content\home;
use \lib\saloos;

class controller extends \mvc\controller
{
	public function config()
	{
		if($this->login())
			var_dump($this->login('all'));
	}

	// for routing check
	function _route()
	{

	}
}
?>
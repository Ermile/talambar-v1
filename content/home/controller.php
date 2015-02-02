<?php
namespace content\home;
use \lib\saloos;

class controller extends \mvc\controller
{
	public function config()
	{
		// if($this->login())
		// 	var_dump($this->login('all'));
	}

	// for routing check
	function _route()
	{
		// if comming from login service then redirect to files
		$referer = \lib\router::urlParser('referer', 'domain');
		if($referer === MainService)
			$this->redirector()->set_domain('files.'.Service)->set_url();

	}
}
?>
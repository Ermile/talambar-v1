<?php
namespace content_files\home;

class controller extends \mvc\controller
{
	function _route()
	{
		if(!$this->login())
		{
			\lib\debug::warn(T_("first of all, you must login to system!"));
			$this->redirector(null, false)->set_domain($this->url('AccountService'))->set_url('login?dev=y')->redirect();
			exit();
		}


		$mymodule = $this->module();

		// show all data on this subdomain
		$x = $this->post('upload')->ALL('upload');
		$x = $this->get('killSession')->ALL('killSession');
		$x = $this->get('resume')->ALL('resume');
		// $this->post('folder')->ALL("folder");


		// please don't comment below line!
		$this->get()->ALL();
	}
}
?>
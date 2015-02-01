<?php
namespace content_files\home;

class controller extends \mvc\controller
{
	function _route()
	{
		$mymodule = $this->module();
		$mychild	 = $this->child();

		// show all data on this subdomain
		$this->get()->ALL();
		$this->post('upload')->ALL("/^(upload|resume)$/");
		$this->post('folder')->ALL("folder");
	}

}
?>
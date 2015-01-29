<?php
namespace content_files\home;

class controller extends \mvc\controller
{
	function _route()
	{
		$mymodule = $this->module();
		$mychild	 = $this->child();

		// $this->get(null, 'datatable')->ALL();
	}
}
?>
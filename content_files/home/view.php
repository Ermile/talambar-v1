<?php
namespace content_files\home;

class view extends \content_files\main\view
{
	public function config()
	{
		$this->data->list 	= $this->cpModlueList('all');
	}
}
?>
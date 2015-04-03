<?php
namespace content_files\home;

class view extends \mvc\view
{
	public function config()
	{
		$this->include->js          = false;
		$this->include->css         = false;
		$this->include->fontawesome = true;
		$this->data->bodyclass      = 'unselectable';

		$this->data->location       = $this->url('path', -1);

		$this->global->tree         = json_encode($this->model()->tree());		
	}
}
?>
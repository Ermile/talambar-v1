<?php
namespace content_files\home;

class view extends \mvc\view
{
	public function config()
	{
		$this->data->list      = $this->model()->directories();;
		// var_dump($this->data->list);
		$this->data->bodyclass = 'fixed';
		$this->include->js     = false;
	}
}
?>
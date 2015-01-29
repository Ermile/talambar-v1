<?php
namespace content_files\home;

class view extends \mvc\view
{
	public function config()
	{
		$this->data->bodyclass = 'fixed';
		$this->include->js     = false;
		$this->data->location  = $this->url('path', -1);

		// var_dump($this->data->location);

		$this->data->list      = $this->model()->directories($this->data->location);
		// var_dump($this->data->list);
	}
}
?>
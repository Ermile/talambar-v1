<?php
namespace content_files\home;

class view extends \mvc\view
{
	public function config()
	{
		$this->data->bodyclass      = 'fixed';
		$this->include->js          = false;
		$this->include->css         = false;
		$this->include->fontawesome = true;
		$this->data->location       = $this->url('path', -1);
		
		// var_dump($this->data->location);
		
		$this->data->list           = $this->model()->directories_hasan($this->data->location);

		// var_dump($this->data->list);
		$this->data->yourlocation   = $this->model()->directories($this->data->location);
		// var_dump($this->data->yourlocation);
		
		$this->data->breadcrumb     = $this->breadcrumb();
	}


	public function breadcrumb()
	{
		$myurl        = \lib\router::urlParser('current', 'path', 'array');
		$mybreadcrumb = array();

		foreach ($myurl as $key => $value)
		{
			$myaddr = '';
			for ($i=0; $i <= $key ; $i++)
				$myaddr .= $myurl[$i]. '/';

			$mybreadcrumb[trim($myaddr, '/')] = $value;
		}

		return $mybreadcrumb;
	}

}
?>
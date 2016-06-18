<?php
namespace mvc;

class view extends \lib\mvc\view
{
	/**
	 * [_construct description]
	 * @return [type] [description]
	 */
	function _construct()
	{
		// define default value for global

		$this->data->site['title']   = T_("Talambar");
		$this->data->site['desc']    = T_("Another cloud storage!"). ' '.
			T_("Indeed we respect for privacy and try to providing maximum security for your files.");
		$this->data->site['slogan']  = T_("Talambar is our project");

		// $this->data->page['title']   = T_("Archiver");
		$this->data->page['desc']    = T_("Talambar is Inteligent.");
		$this->data->page['desc']    = $this->data->site['desc'];


		// $this->url->MainStatic       = false;

		// if you need to set a class for body element in html add in this value
		// $this->data->bodyclass      = null;

		$this->data->display['files']     = "content_files/home/layout.html";

		if (!locale_emulation()) {
			$this->include->gettext  = 'Translation use native gettext dll';
		}
		else {
			$this->include->gettext  = 'Translation use PHP gettext class';
		}
	}


	/**
	 * [options description]
	 * @return [type] [description]
	 */
	function options()
	{
		// $this->data->feature['posts']             = false;
		// $this->data->feature['pages']             = false;
		// $this->data->feature['attachments']       = false;
		// $this->data->feature['tags']              = true;
		// $this->data->feature['categories']        = false;
		// $this->data->feature['users']             = true;
		// $this->data->feature['permissions']       = true;
		// $this->data->feature['options']['sms']    = false;
		// $this->data->feature['options']['social'] = false;
	}


	/**
	 * [pushState description]
	 * @return [type] [description]
	 */
	function pushState()
	{
		$this->data->display['files']     = "content_files/home/layout-xhr.html";
	}
}
?>
<?php
namespace mvc;

class view extends \lib\mvc\view
{
	function _construct()
	{
		// define default value for global

		$this->data->site['title']   = T_("Talambar");
		$this->data->site['desc']    = T_("Talambar is new");
		$this->data->site['slogan']  = T_("Talambar is our project");

		$this->data->page['desc']    = T_("Talambar is Inteligent.");

		// add language list for use in display
		$this->global->langlist		= array(
												'fa_IR' => 'فارسی',
												'en_US' => 'English',
												'de_DE' => 'Deutsch'
												);

		// if you need to set a class for body element in html add in this value
		// $this->data->bodyclass      = null;

		if (!locale_emulation()) {
			$this->include->gettext  = 'Translation use native gettext dll';
		}
		else {
			$this->include->gettext  = 'Translation use PHP gettext class';
		}
	}

	function pushState()
	{
		// $this->data->display['files'] = "content_files/home/xhr-layout.html";
		$this->data->display['cp']    = "content_cp/main/xhr-layout.html";
	}

}
?>
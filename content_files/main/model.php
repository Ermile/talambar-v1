<?php
namespace content_files\main;
use \lib\utility;
use \lib\debug;

class model extends \mvc\model
{
	function get_delete()	{ $this->delete(); }
	function post_delete()	{ $this->delete(); }
	function post_add()  	{ $this->insert(); }
	function post_edit() 	{ $this->update(); }
	function post_options() { return 'soon';   }


	public function test()
	{
		// var_dump($this->login('id'));
		// 
		// 
		$tmp_table = $this->sql()->tableAttachments()->whereAttachment_title('hasan')->select();
		var_dump($tmp_table);
		// var_dump($tmp_table->string());


		// var_dump($tmp_table->allassoc());


	}
}
?>
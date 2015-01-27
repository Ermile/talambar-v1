<?php
namespace content_files\main;
use \lib\utility;
use \lib\debug;

class model extends \mvc\model
{
	// join sample!
	// $sql = $this->sql->tableUsers()->whereId(10);
	// $sql->joinMobile()->whereId("#users.id")
	// SELECT * FROM users inner join mobile where mobile.id = users.id

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
		// $tmp_table = $this->sql()->tableAttachments()->whereUser_id(190)->select();
		// var_dump($tmp_table);
		// var_dump($tmp_table->string());


		// var_dump($tmp_table->allassoc());


	}
}
?>
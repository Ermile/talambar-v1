<?php
namespace database\ermile;
class upload 
{
	public $id 		= array('null' =>'NO',  'show' =>'NO',  'label'=>'Id',  'type' => 'int@10',);
	public $part		= array('null' =>'YES',  'show' =>'YES', 'label'=>'Name', 'type' => 'int@10',);
	public $session	= array('null' =>'NO',  'show' =>'YES', 'label'=>'Slug', 'type' => 'varchar@32');
	public $user_id	= array('null' =>'NO',  'show' =>'YES', 'label'=>'Name', 'type' => 'int@10',);


	public function id() {$this->validate()->id();}
	// public function part() 
	// {
	// 	// $this->validate()
	// }
}
?>
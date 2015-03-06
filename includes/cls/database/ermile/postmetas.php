<?php
namespace database\ermile;
class postmetas 
{
	public $id              = array('null' =>'NO',  'show' =>'NO',  'label'=>'id',            'type' => 'int@10',                            );
	public $post_id         = array('null' =>'NO',  'show' =>'YES', 'label'=>'post',          'type' => 'smallint@5',                        'foreign'=>'posts@id!post_title');
	public $postmeta_cat    = array('null' =>'NO',  'show' =>'YES', 'label'=>'cat',           'type' => 'varchar@50',                        );
	public $postmeta_name   = array('null' =>'NO',  'show' =>'YES', 'label'=>'name',          'type' => 'varchar@100',                       );
	public $postmeta_value  = array('null' =>'YES', 'show' =>'YES', 'label'=>'value',         'type' => 'varchar@999',                       );
	public $postmeta_status = array('null' =>'NO',  'show' =>'YES', 'label'=>'status',        'type' => 'enum@enable,disable,expire!enable', );
	public $date_modified   = array('null' =>'YES', 'show' =>'NO',  'label'=>'modified',      'type' => 'timestamp@',                        );


	//------------------------------------------------------------------ id
	public function id() {$this->validate()->id();}

	//------------------------------------------------------------------ id - foreign key
	public function post_id() 
	{
		$this->form("select")->name("post_")->min(0)->max(99999)->required()->type("select")->validate()->id();
		$this->setChild();
	}
	public function postmeta_cat() 
	{
		$this->form("text")->name("cat")->maxlength(50)->required()->type('text');
	}
	public function postmeta_name() 
	{
		$this->form("text")->name("name")->maxlength(100)->required()->type('text');
	}
	public function postmeta_value() 
	{
		$this->form("text")->name("value")->maxlength(999)->type('textarea');
	}

	//------------------------------------------------------------------ select button
	public function postmeta_status() 
	{
		$this->form("select")->name("status")->type("select")->required()->validate();
		$this->setChild();
	}
	public function date_modified() {}
}
?>
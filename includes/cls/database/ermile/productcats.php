<?php
namespace database\ermile;
class productcats 
{
	public $id                = array('null' =>'NO',  'show' =>'NO',  'label'=>'id',            'type' => 'smallint@5',                        );
	public $productcat_title  = array('null' =>'NO',  'show' =>'YES', 'label'=>'title',         'type' => 'varchar@50',                        );
	public $productcat_slug   = array('null' =>'NO',  'show' =>'YES', 'label'=>'slug',          'type' => 'varchar@50',                        );
	public $productcat_desc   = array('null' =>'YES', 'show' =>'NO',  'label'=>'desc',          'type' => 'varchar@200',                       );
	public $productcat_parent = array('null' =>'YES', 'show' =>'YES', 'label'=>'parent',        'type' => 'smallint@5',                        );
	public $attachment_id     = array('null' =>'YES', 'show' =>'YES', 'label'=>'attachment',    'type' => 'int@10',                            'foreign'=>'attachments@id!attachment_title');
	public $productcat_order  = array('null' =>'YES', 'show' =>'YES', 'label'=>'order',         'type' => 'smallint@5',                        );
	public $productcat_status = array('null' =>'NO',  'show' =>'YES', 'label'=>'status',        'type' => 'enum@enable,disable,expire!enable', );
	public $productcat_count  = array('null' =>'YES', 'show' =>'YES', 'label'=>'count',         'type' => 'smallint@5',                        );
	public $date_modified     = array('null' =>'YES', 'show' =>'NO',  'label'=>'modified',      'type' => 'timestamp@',                        );


	//------------------------------------------------------------------ id
	public function id() {$this->validate()->id();}

	//------------------------------------------------------------------ title
	public function productcat_title() 
	{
		$this->form("#title")->maxlength(50)->required()->type('text');
	}

	//------------------------------------------------------------------ slug
	public function productcat_slug() 
	{
		$this->form("#slug")->maxlength(50)->required()->type('text');
	}

	//------------------------------------------------------------------ desc
	public function productcat_desc() 
	{
		$this->form("#desc")->maxlength(200)->type('textarea');
	}
	public function productcat_parent() 
	{
		$this->form("text")->name("parent")->min(0)->max(99999)->type('number');
	}

	//------------------------------------------------------------------ id - foreign key
	public function attachment_id() 
	{
		$this->form("select")->name("attachment_")->min(0)->max(9999999999)->type("select")->validate()->id();
		$this->setChild();
	}
	public function productcat_order() 
	{
		$this->form("text")->name("order")->min(0)->max(99999)->type('number');
	}

	//------------------------------------------------------------------ select button
	public function productcat_status() 
	{
		$this->form("select")->name("status")->type("select")->required()->validate();
		$this->setChild();
	}
	public function productcat_count() 
	{
		$this->form("text")->name("count")->min(0)->max(99999)->type('number');
	}
	public function date_modified() {}
}
?>
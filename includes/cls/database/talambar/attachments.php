<?php
namespace database\talambar;
class attachments
{
	public $id                = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'id'              ,'type'=>'bigint@20'];
	public $file_id           = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'file'            ,'type'=>'bigint@20'                       ,'foreign'=>'files@id!id'];
	public $attachment_title  = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'title'           ,'type'=>'varchar@100'];
	public $attachment_desc   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'desc'            ,'type'=>'varchar@500'];
	public $attachment_type   = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'type'            ,'type'=>'enum@system,other,file,folder'];
	public $attachment_addr   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'addr'            ,'type'=>'varchar@1000'];
	public $attachment_name   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'name'            ,'type'=>'varchar@100'];
	public $attachment_ext    = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'ext'             ,'type'=>'varchar@255'];
	public $attachment_size   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'size'            ,'type'=>'float@12,0'];
	public $attachment_meta   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'meta'            ,'type'=>'mediumtext@'];
	public $attachment_parent = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'parent'          ,'type'=>'bigint@20'                       ,'foreign'=>'attachments@id!attachment_title'];
	public $attachment_order  = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'order'           ,'type'=>'smallint@5'];
	public $attachment_status = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'status'          ,'type'=>'enum@normal,trash,deleted,inprogress,unavailable,hidden!normal'];
	public $attachment_fav    = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'fav'             ,'type'=>'bit@1'];
	public $attachment_date   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'date'            ,'type'=>'datetime@'];
	public $user_id           = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'user'            ,'type'=>'int@10'                          ,'foreign'=>'users@id!user_displayname'];
	public $date_modified     = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'modified'        ,'type'=>'timestamp@'];

	//--------------------------------------------------------------------------------id
	public function id(){}
	//--------------------------------------------------------------------------------foreign
	public function file_id()
	{
		$this->form()->type('select')->name('file_');
		$this->setChild();
	}

	public function attachment_title()
	{
		$this->form('#title')->type('text')->name('title')->maxlength('100');
	}

	public function attachment_desc()
	{
		$this->form('#desc')->type('textarea')->name('desc')->maxlength('500');
	}

	public function attachment_type()
	{
		$this->form()->type('radio')->name('type')->required();
		$this->setChild();
	}

	public function attachment_addr()
	{
		$this->form()->type('textarea')->name('addr')->maxlength('1000');
	}

	public function attachment_name()
	{
		$this->form()->type('text')->name('name')->maxlength('100');
	}

	public function attachment_ext()
	{
		$this->form()->type('textarea')->name('ext')->maxlength('255');
	}

	public function attachment_size()
	{
		$this->form()->type('number')->name('size')->min()->max('999999999999');
	}

	public function attachment_meta(){}

	public function attachment_parent()
	{
		$this->form()->type('select')->name('parent');
		$this->setChild();
	}

	public function attachment_order()
	{
		$this->form()->type('number')->name('order')->min()->max('99999');
	}

	public function attachment_status()
	{
		$this->form()->type('radio')->name('status')->required();
		$this->setChild();
	}

	public function attachment_fav(){}

	public function attachment_date()
	{
		$this->form()->type('text')->name('date');
	}
	//--------------------------------------------------------------------------------foreign
	public function user_id()
	{
		$this->form()->type('select')->name('user_')->required();
		$this->setChild();
	}

	public function date_modified(){}
}
?>
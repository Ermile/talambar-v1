<?php
namespace database\talambar;
class files
{
	public $id              = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'id'              ,'type'=>'bigint@20'];
	public $file_server     = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'server'          ,'type'=>'smallint@5'];
	public $file_folder     = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'folder'          ,'type'=>'int@10'];
	public $file_code       = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'code'            ,'type'=>'varchar@64'];
	public $file_size       = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'size'            ,'type'=>'bigint@20'];
	public $file_meta       = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'meta'            ,'type'=>'mediumtext@'];
	public $file_status     = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'status'          ,'type'=>'enum@inprogress,ready,temp,delete,unavailable'];
	public $file_createdate = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'createdate'      ,'type'=>'datetime@'];
	public $date_modified   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'modified'        ,'type'=>'timestamp@'];

	//--------------------------------------------------------------------------------id
	public function id(){}

	public function file_server()
	{
		$this->form()->type('number')->name('server')->min()->max('99999')->required();
	}

	public function file_folder()
	{
		$this->form()->type('number')->name('folder')->min()->max('9999999999')->required();
	}

	public function file_code()
	{
		$this->form()->type('text')->name('code')->maxlength('64')->required();
	}

	public function file_size()
	{
		$this->form()->type('number')->name('size')->min()->max('99999999999999999999')->required();
	}

	public function file_meta(){}

	public function file_status()
	{
		$this->form()->type('radio')->name('status')->required();
		$this->setChild();
	}

	public function file_createdate(){}

	public function date_modified(){}
}
?>
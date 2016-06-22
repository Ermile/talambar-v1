<?php
namespace database\talambar;
class attachmentmetas
{
	public $id                    = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'id'              ,'type'=>'bigint@20'];
	public $attachment_id         = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'attachment'      ,'type'=>'bigint@20'                       ,'foreign'=>'attachments@id!id'];
	public $attachmentmeta_cat    = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'cat'             ,'type'=>'varchar@50'];
	public $attachmentmeta_key    = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'key'             ,'type'=>'varchar@100'];
	public $attachmentmeta_meta   = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'meta'            ,'type'=>'mediumtext@'];
	public $attachmentmeta_value  = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'value'           ,'type'=>'varchar@200'];
	public $attachmentmeta_status = ['null'=>'NO'  ,'show'=>'YES'     ,'label'=>'status'          ,'type'=>'enum@enable,disable,expire!enable'];
	public $date_modified         = ['null'=>'YES' ,'show'=>'YES'     ,'label'=>'modified'        ,'type'=>'timestamp@'];

	//--------------------------------------------------------------------------------id
	public function id(){}
	//--------------------------------------------------------------------------------foreign
	public function attachment_id()
	{
		$this->form()->type('select')->name('attachment_')->required();
		$this->setChild();
	}

	public function attachmentmeta_cat()
	{
		$this->form()->type('text')->name('cat')->maxlength('50')->required();
	}

	public function attachmentmeta_key()
	{
		$this->form()->type('text')->name('key')->maxlength('100')->required();
	}

	public function attachmentmeta_meta(){}

	public function attachmentmeta_value()
	{
		$this->form()->type('textarea')->name('value')->maxlength('200');
	}

	public function attachmentmeta_status()
	{
		$this->form()->type('radio')->name('status')->required();
		$this->setChild();
	}

	public function date_modified(){}
}
?>
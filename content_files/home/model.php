<?php
namespace content_files\home;
use \lib\debug;
use \lib\utility;
class model extends \mvc\model
{
	public function tree()
	{
		$myid = $this->login('id');

		// create query for get by folders name and ordered by depth
		$qry   = $this->sql()->tableAttachments()->whereUser_id($myid)
		->orderAttachment_depth('ASC')->orderAttachment_order('ASC')->select();

		$mydatatable = array();
		foreach ($qry->allassoc() as $row)
		{
			$mydatatable[] = array(
				'name'   => $row['attachment_title'],
				'id'     => $row['id'],
				'type'   => $row['attachment_type'],
				'ext'    => $row['attachment_ext'],
				'size'   => $row['attachment_size'],
				'count'  => $row['attachment_count'],
				'order'  => $row['attachment_order'],
				'parent' => $row['attachment_parent'],
				);
		}
		return $mydatatable;
	}

	public function post_folder(){
	}

	/**
	 * set tree in attachments table
	 * @param [int] 			$parent_id parent id
	 * @param [string] 			$name      tree name
	 * @param [boolean or int] 	$isFile    if is file is int and if is folder is null
	 */
	private function set_tree($parent_id, $name, $isFile = null){
		$uid = $this->login('id');
		$sql = $this->sql("set_tree")->tableAttachments();
		if($isFile !== null){
			$sql->setAttachment_type('file');
			$sql->setFile_id($isFile);
		}else{
			$sql->setAttachment_type('folder');
		}
		$sql = $sql->setAttachment_parent($parent_id)
		->setAttachment_title($name)
		->setUser_id($uid)->insert();
		if($sql->LAST_INSERT_ID() !== false){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * get parent or folder id
	 * @param  [string] $addr 	path directory
	 * @param  [string] $name 	file or folder name
	 * @param  [boolean] $isFile 	is file or folder
	 * @return [int]       		parent_id
	 */
	private function getFolder_id($addr, $name, $isFile = null){
		$uid = $this->login('id');
		if($addr == "/") $parent_id = "#NULL";
		else{
			$sAddr = explode("/", $addr);
			$pTitle = end($sAddr);
			array_pop($sAddr);
			$addr = join("/", $sAddr);
			if($addr == "") $addr = "/";
			$sql = $this->sql("getParent_id")->tableAttachments()
			->fieldId()
			->whereUser_id($uid)
			->andAttachment_addr($addr)
			->andAttachment_type("folder")
			->andAttachment_title($pTitle)
			->limit(1);
			$parent_id = $sql->select()->assoc('id');
		}
		if(is_numeric($parent_id) || $parent_id == "#NULL"){
			$exists = $this->sql("checkexists")->tableAttachments()
			->fieldId()
			->whereUser_id($uid);

			if($isFile){
				$exists->andAttachment_type('file');
			}else{
				$exists->andAttachment_type('folder');
			}

			$exists = $exists->andAttachment_title($name)
			->andAttachment_parent($parent_id)
			->limit(1)->select()->num();
			if($exists){
				return false;
			}else{
				return $parent_id;
			}
		}else{
			return false;
		}
	}

	public function post_upload(){
		$tmp = root.'../tmp-upd/';
		$uid = $this->login('id');
		$session = utility::post("session");
		$file = $_FILES['file'];
		if($session == '0'){

			/**
			 * files table
			 */
			$size = utility::post("size");
			$open_file_row = $this->sql('upload')->tableFiles()
			->setFile_size($size)
			->setFile_status('inprogress')
			->insert();
			$file_id = $open_file_row->LAST_INSERT_ID();
			if($file_id === false){
				debug::error('file row error', 'open', 'file');
				return;
			}

			/**
			 * attachments table
			 */
			$parent = utility::post("parent");
			if(!$parent){
				$site = urldecode($_SERVER['HTTP_REFERER']);
				preg_match("/^https?:\/\/[^\/]*(\/.*)$/", $site, $path);
				$parent = $path[1];
			}
			$parent_id = $this->getFolder_id($parent, $file['name'], true);
			if(!$parent_id){
				debug::error('path directory error', 'path', 'file');
				return;
			}
			$set = $this->set_tree($parent_id, $file['name'], $file_id);
			if(!$set){
				debug::error('attachment save error', 'attachments', 'file');
				return;
			}

			/**
			 * fileparts talbe
			 */
			$session = md5(time().rand(111111111, 999999999));
			$open_file_parts = $this->sql('upload')->tableFileparts()
			->setFile_id($file_id)
			->setFilepart_part("#0")
			->setFilepart_code($session)
			->setFilepart_status('inprogress')
			->insert()->LAST_INSERT_ID();
			if(!is_dir($tmp)) mkdir($tmp);
			mkdir($tmp.$file_id);
			if(!debug::$status || !move_uploaded_file($_FILES['file']['tmp_name'], $tmp.$file_id.'/0')){
				debug::error('upload file error', 'move', 'file');
				return;
			}
			debug::property("session", $session);
			debug::property("file", $file_id);
		}else{
			$file_id = $file['name'];
			$file_parts_check = $this->sql('upload')->tableFileparts()
			->whereFile_id($file_id)->limit(1)->select();
			$file_parts = $file_parts_check->assoc();
			if(!$file_parts['filepart_code'] == $session && $file_parts['filepart_code'] != ''){
				debug::error('file in progress in another client', 'session', 'file');
				return;
			}elseif($file_parts['filepart_status'] == 'appended'){
				debug::error('file in appending', 'appending', 'file');
				return;
			}
			$this->sql('upload')->tableFileparts()
			->setFilepart_part("#filepart_part+1")
			->whereFile_id($file_id)
			->update();
			$new_part = $file_parts['filepart_part'] +1;
			if(!debug::$status || !move_uploaded_file($_FILES['file']['tmp_name'], $tmp.$file_id.'/'.$new_part)){
				debug::error('upload file error', 'move', 'file');
				return;
			}
		}
	}

	public function get_killSession(){
		$session = utility::get("session");
		$finished = utility::get("finished");
		$file_parts = $this->sql('upload')->tableFileparts()
		->setFilepart_code($session);
		if($finished === 'true'){
			$file_parts->setFilepart_status('appended');
		}
		$file_parts = $file_parts->whereFilepart_code($session)->update();
		if($finished === 'true' && debug::$status){
			$parts_check = $this->sql('upload')->tableFileparts()
			->whereFilepart_code($session)
			->limit(1)
			->select();
			$this->appending($parts_check->assoc('file_id'));
		}

		$opt = (object) array('force_json' => true, 'force_stop' => true);
		$this->_processor($opt);
	}

	private function appending($file_id){
		$tmp = root.'../tmp-upd/'.$file_id.'/';
		$part = 0;
		while ($file = @file_get_contents($tmp.$part)) {
			file_put_contents($tmp.'final', $file, FILE_APPEND);
			$part++;
		}
	}
}
/*
Array
(
    [session] => 0
)
Array
(
    [file] => Array
        (
            [name] => friday.mp3
            [type] => application/octet-stream
            [tmp_name] => /tmp/phpl7IiQF
            [error] => 0
            [size] => 200000
        )

)
 */
?>
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
		$qry   = $this->sql()->tableAttachments()->whereUser_id($myid)->andAttachment_type('folder')
		->orderAttachment_depth('ASC')->orderAttachment_order('ASC')->select();

		$mydatatable = array();
		foreach ($qry->allassoc() as $row)
		{
			$mydatatable[] = array(
				'name'     => $row['attachment_title'],
				'id'     => $row['id'],
				'type'   => $row['attachment_type'],
				'ext'    => $row['attachment_ext'],
				'count'  => $row['attachment_count'],
				'order'  => $row['attachment_order'],
				'parent' => $row['attachment_parent'],
				);
		}
		return $mydatatable;
	}

	// return tree of all directories with subdirectories at all
	public function test_dir()
	{
		$myid = $this->login('id');

		// create query for get by folders name and ordered by depth
		$qry   = $this->sql()->tableAttachments()->whereUser_id($myid)->andAttachment_type('folder')
		->orderAttachment_depth('ASC')->orderAttachment_order('ASC')->select();
		// var_dump($qry->string());
		// @hasan : second order is not work ******************************************* @hasan
		$tmp_result = $qry->allassoc();

		var_dump($tmp_result);
		$mydatatable = array();
		$mydepth     = 0;

		for($_depth = 0; $_depth < 5; $_depth++)
		{
			foreach($variable as $key => $value)
			{
				# code...
			}

			// if($_depth == $tmp_result['attachment_depth'])
			// {

			// }
			// else
			// {
			// 	var_dump('break');
			// 	break;
			// }
		}

		return;
		foreach ($tmp_result as $row)
		{
			if($mydepth == $row['attachment_depth'])
				var_dump(123);


			$mydatatable[$row['attachment_parent']] = array(
				// 'type'   => $row['attachment_type'],
				// 'ext'    => $row['attachment_ext'],
				'depth'  => $row['attachment_depth'],
				'count'  => $row['attachment_count'],
				'order'  => $row['attachment_order'],
				'parent' => $row['attachment_parent'],
				);

			// $mydatatable[$row['attachment_title']] = array(
			// 	// 'type'   => $row['attachment_type'],
			// 	// 'ext'    => $row['attachment_ext'],
			// 	'depth'  => $row['attachment_depth'],
			// 	'count'  => $row['attachment_count'],
			// 	'order'  => $row['attachment_order'],
			// 	'parent' => $row['attachment_parent'],
			// );
		}
		// var_dump($tmp_result);
		var_dump($mydatatable);

		return $mydatatable;
	}


	public function directories($_location = null)
	{
		// now we only support only 9 subfolder!
		if(count($_location)>9)
			return null;

		// $myid = 190;
		$myid = $this->login('id');

		// create query for get by folders name and ordered by depth
		$qry   = $this->sql()->tableAttachments()->whereUser_id($myid)->group('open');
		$field = null;
		foreach ($_location as $value)
		{
			$field = (is_null($field)? 'and': 'or' ). 'Attachment_title';
			$qry   = $qry->$field($value);
		}
		$qry        = $qry->orderAttachment_depth('ASC')->group('close')->select();
		$tmp_result = $qry->allassoc();

		// in this loop we check with depth number and if not correct return null
		$is_invalid = false;
		$myfolderid = $myid;
		foreach ($_location as $key => $value)
		{
			if(isset($tmp_result[$key]) && $value === $tmp_result[$key]['attachment_title'])
			{
				$myfolderid = $tmp_result[$key]['id'];
			}
			else
				$is_invalid = true;
		}

		if($is_invalid)
		{
			// show message to user for incorrect folder
			debug::true(T_("this folder does not exist!"));
			return;
		}


		$tmp_result  = $this->sql()->tableAttachments()
		->whereUser_id          ($myid)
		->andAttachment_depth   (count($_location))
		->andAttachment_parent  ($myfolderid)
		->orderAttachment_order ('ASC')
		->select();
		// var_dump($tmp_result->string());

		$mydatatable = array();
		foreach ($tmp_result->allassoc() as $row)
		{
			$mydatatable[$row['attachment_title']] = array(
				'type'   => $row['attachment_type'],
				'ext'    => $row['attachment_ext'],
				'depth'  => $row['attachment_depth'],
				'count'  => $row['attachment_count'],
				'order'  => $row['attachment_order'],
				'parent' => $row['attachment_parent'],
				);
		}
		return $mydatatable;
	}

	// @hasan: edit below lines *************************




	public function directories_hasan($_location = null)
	{
		// now we only support only 9 subfolder!
		if(count($_location)>9)
			return null;

		// $myid = $this->login('id');
		$myid = 190;
		$location = "/".join("/", $_location);
		// create query for get by folders name and ordered by depth
		$qry   = $this->sql()->tableAttachments()->whereUser_id($myid)->andAttachment_addr($location);
		// $field = null;
		// foreach ($_location as $value)
		// {
		// 	$field = (is_null($field)? 'and': 'or' ). 'Attachment_title';
		// 	$qry   = $qry->$field($value);
		// }
		
		$qry        = $qry->orderAttachment_depth('ASC')->group('close')->select();
		$tmp_result = $qry->allassoc();
		// in this loop we check with depth number and if not correct return null
		$is_invalid = false;
		$myfolderid = "#NULL";
		// foreach ($_location as $key => $value)
		// {
		// 	if(isset($tmp_result[$key]) && $value === $tmp_result[$key]['attachment_title'])
		// 	{
		// 		$myfolderid = $tmp_result[$key]['id'];
		// 	}
		// 	else
		// 		$is_invalid = true;
		// }

		// if($is_invalid)
		// {
		// 	// show message to user for incorrect folder
		// 	debug::true(T_("this folder does not exist!"));
		// 	return;
		// }


		$tmp_result  = $this->sql()->tableAttachments()
		->whereUser_id          ($myid)
		// ->andAttachment_depth   (count($_location))
		->andAttachment_addr  ($location)
		->orderAttachment_order ('ASC')
		->select();
		$mydatatable = array();
		foreach ($tmp_result->allassoc() as $row)
		{
			$mydatatable[$row['attachment_title']] = array(
				'type'   => $row['attachment_type'],
				'ext'    => $row['attachment_ext'],
				'depth'  => $row['attachment_depth'],
				'count'  => $row['attachment_count'],
				'order'  => $row['attachment_order'],
				'parent' => $row['attachment_parent'],
				'addr'   => $row['attachment_addr'],
				);
		}
		return $mydatatable;
	}

	public function post_folder(){
		exit();
		$uid = 190;
		$parent = utility::post("parent");
		if(!$parent){
			$site = urldecode($_SERVER['HTTP_REFERER']);
			preg_match("/^https?:\/\/[^\/]*(\/.*)$/", $site, $path);
			$parent = $path[1];
		}
		$name = utility::post("folder");
		$parent_id = $this->getFolder_id($parent, $name);
		if(!$parent_id) debug::error("duplicate entry", "folder", "form");
		$sql = $this->sql("make_file")->tableAttachments()
		->setAttachment_type("folder")
		->setAttachment_parent($parent_id)
		->setAttachment_title($name)
		->setAttachment_addr($parent)
		->setAttachment_depth(count(explode('/', $parent))-1)
		->setUser_id($uid)->insert();
		$this->commit(function(){
			debug::title("saved");
		});
		$this->commit(function(){
			debug::title("error");
		});
	}

	private function getFolder_id($addr, $name){
		$uid = 190;
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
			echo $sql->select()->string()."\n";
			$parent_id = $sql->select()->assoc('id');
		}
		if(is_numeric($parent_id) || $parent_id == "#NULL"){
			$isFolder = $this->sql("checkIsFolder")->tableAttachments()
			->fieldId()
			->whereUser_id($uid)
			->andAttachment_title($name)
			->andAttachment_parent($parent_id)
			->limit(1)->select()->num();
			if($isFolder){
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
		$uid = $_SESSION['user']['id'];
		$session = utility::post("session");
		$query_upload = $this->sql('upload')->tableUpload();
		if($session == '0'){
			$session = md5(time());
			$file_id = $query_upload->setPart("#0")
			->setSession($session)
			->setUser_id($uid)
			->insert()->LAST_INSERT_ID();
			debug::property("session", $session);
			debug::property("file", $file_id);
		}else{
			$file_id = $_FILES['file']['name'];
			$check_file = $query_upload->whereSession($session)
			->andUser_id($uid)
			->andId($file_id)
			->limit(1)
			->select();
			if(!$check_file->num()){
				debug::error('file not found', 'upload', 'file');
			}
		}
		
		// exit();
		// print_r($_FILES);
		// print_r(apache_request_headers());
		// debug::property("session", );
		// $this->_processor();
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
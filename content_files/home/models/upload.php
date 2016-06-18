<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait upload
{
	/**
	 * get file id from files table
	 * @param  [type] $_md5 md5 of file for search in db
	 * @return [type]       id of the requested file
	 */
	protected function upload_fileID($_md5 = null)
	{
		if(!$_md5)
		{
			$_md5 = utility\upload::$fileMd5;
		}

		$qry_FileID = $this->sql()
			->table('files')
			->where('file_code', $_md5)
			->select();

		if($qry_FileID->num() == 1)
		{
			return $qry_FileID->assoc('id');
			$file_exist = true;
			// file exist in db files, use old one and dont use new file
			// $new_file_id = $qry_FileID->assoc('id');
			// $id = $qry_FileID->assoc('id');
			// debug::property('status','ok');
			// $link = '<a target="_blank" href=/attachments/edit='. $id. '>'. T_('Duplicate - File exist').'</a>';
			// debug::property('error', $link);

			// $this->_processor(['force_json'=>true, 'not_redirect'=>true]);
			// return false;
		}
		return false;
	}


	/**
	 * doing upload process
	 * @return [boolean] if status is false, return it
	 */
	public function post_upload()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'upload', 'add', 'notify');

		$_location = $this->getLocation();

		if(!$_location || strpos($_location, '/$/') !== false)
		{
			debug::property('status','fail');
			debug::property('error', T_("You can't upload in this location!"));

			$this->_processor(['force_json'=>true, 'not_redirect'=>true]);
			return false;
		}

		$FOLDER_SIZE = 1000;
		$SERVER_SIZE = 1000000;		// 1 milion file can save in each server
		$server_id   = 1;
		// utility\upload::$extentions = 'all';

		// 1. check upload process and validate it
		$invalid = utility\upload::invalid('upfile');
		if($invalid)
		{
			debug::property('status','fail');
			debug::property('error', $invalid);

			$this->_processor(['force_json'=>true, 'not_redirect'=>true]);
			return false;
		}


		// 2. Generate file_id, folder_id and url
		$qry_count     = $this->sql()->table('files')->select()->num();
		$folder_prefix = "data/";
		$folder_id     = ceil(($qry_count + 1) / $FOLDER_SIZE);
		$folder_name   = 'data/' . $folder_id;
		$file_id       = $qry_count % $FOLDER_SIZE + 1;
		$file_ext      = utility\upload::$fileExt;
		$file_url      = "$folder_name/$file_id";
		// $file_dl       = utility\upload::$fileFullName. '.'. $file_ext;
		// $url_full      = "$folder_name/$file_id";
		// $url_full      = "$folder_name/$file_id-" . utility\upload::$fileFullName;
		// $url_full      = "$folder_name/$file_id." . $file_ext;



		// 3. Check for record exist in db or not then if not exist transfer it to data folder
		$qry_count2 = $this->sql()->table('files')->where('file_code', utility\upload::$fileMd5)->select();
		$file_exist = false;
		if($qry_count2->num())
		{
			$file_exist = true;
			// file exist in db files, use old one and dont use new file

			$new_file_id = $qry_count2->assoc('id');
			$file_id     = $new_file_id;
			$file_url    = "$folder_name/$file_id";


			// $id = $qry_count2->assoc('id');
			// debug::property('status','ok');
			// $link = '<a target="_blank" href=/attachments/edit='. $id. '>'. T_('Duplicate - File exist').'</a>';
			// debug::property('error', $link);

			// $this->_processor(['force_json'=>true, 'not_redirect'=>true]);
			// return false;
		}
		else
		{
			// 3.5. transfer file to project folder with new name
			if(!utility\upload::transfer($file_url, $folder_name))
			{
				debug::property('status', 'fail');
				debug::property('error', T_('Fail on tranfering file'));

				$this->_processor(['force_json'=>true, 'not_redirect'=>true]);
				return false;
			}
		}

		// 4. transfer file to project folder with new name
		$url_thumb = null;
		// $url_file  = null;

		// $extlen    = strlen(utility\upload::$fileExt)+1;
		// $url_file  = substr($file_url, 0, -$extlen);

		// $url_file  = $url_file.'.'.utility\upload::$fileExt;

		// 5. get filemeta data
		$file_meta = [
						'url'    => $file_url,
						// 'ext'    => $file_ext,
						'type'   => utility\upload::$fileType,
						'mime'   => utility\upload::$fileMime,
						// 'thumb'  => $url_thumb,
						'file'   => utility\upload::$fileFullName,
					 ];
		$page_url  = $file_meta['type'].'/'.substr($file_url, strlen($folder_prefix));


		switch ($file_ext)
		{
			case 'jpg':
			case 'jpeg':
			case 'tiff':
			case 'png':
			case 'gif':
				// $url_thumb          = $url_file.'-thumb.'.utility\upload::$fileExt;
				$url_thumb          = $file_url.'-thumb';
				$file_meta['thumb'] = $url_thumb;
				utility\image::load($file_url);
				utility\image::thumb(250, 250);
				utility\image::save($url_thumb);
				list($file_meta['width'], $file_meta['height']) = getimagesize($file_url);
				break;

			default:
				break;
		}
		$file_meta = json_encode($file_meta);
		// var_dump($file_meta);
		$file_meta = str_replace('"', '\"', $file_meta);
		// var_dump($file_meta);exit();

		if(!$file_exist)
		{
			// 6. add uploaded file to files table in db
			$qry_files = $this->sql();
			$qry_files = $qry_files->table('files')
						->set('id',               $qry_count + 1)
						->set('file_server',      $server_id)
						->set('file_folder',      $folder_id)
						->set('file_code',        utility\upload::$fileMd5)
						->set('file_size',        utility\upload::$fileSize)
						->set('file_meta',        $file_meta)
						->set('file_status',      'ready')
						->set('file_createdate',  date('Y-m-d H:i:s'));
			$qry_files   = $qry_files->insert();
			// below line can't fetch last id!!
			$new_file_id = $qry_files->LAST_INSERT_ID();
			$new_file_id = $this->upload_fileID();
			// var_dump($new_file_id); exit();
		}

		if(!$new_file_id)
		{
			debug::property('status', 'fail');
			debug::property('error', T_('Fail on registering file'));

			$this->_processor(['force_json'=>true, 'not_redirect'=>true]);
			return false;
		}

		$myFileName = utility\upload::$fileName;
		$myFileName = $this->item_nameChecker($myFileName);



		// 7. add uploaded file record attachment table in db
		$qry = $this->sql();
		$qry = $qry->table('attachments')
					->set('file_id',           $new_file_id)
					->set('attachment_type',   'file')
					->set('attachment_addr',   $_location)
					->set('attachment_name',   $myFileName)
					->set('attachment_ext',    $file_ext)
					->set('attachment_size',   utility\upload::$fileSize)
					->set('attachment_meta',   $file_meta)
					->set('attachment_status', 'normal')
					->set('attachment_date',   date('Y-m-d H:i:s'))
					->set('user_id',           $this->login('id'));
		$qry           = $qry->insert();
		$attachment_id = $qry->LAST_INSERT_ID();


		// 8. commit all changes or rollback and remove file
		// ======================================================
		// you can manage next event with one of these variables,
		// commit for successfull and rollback for failed
		// if query run without error means commit
		$this->commit(function()
		{
			debug::property('status', 'ok');
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::property('status', 'fail');
			debug::property('error', T_('Error'));
			// remove file if has problem
		});

		$this->_processor(['force_json'=>true, 'not_redirect'=>true]);
	}

}
?>
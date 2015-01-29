<?php
namespace content_files\home;
use \lib\debug;

class model extends \mvc\model
{
	public function directories($_location = null)
	{
		// now we only support only 9 subfolder!
		if(count($_location)>9)
			return null;

		// $myid = $this->login('id');
		$myid = 190;

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
				'model'  => $row['attachment_model'],
				'type'   => $row['attachment_type'],
				'depth'  => $row['attachment_depth'],
				'count'  => $row['attachment_count'],
				'order'  => $row['attachment_order'],
				'parent' => $row['attachment_parent'],
			);
		}
		return $mydatatable;
	}
}
?>
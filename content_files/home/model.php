<?php
namespace content_files\home;

class model extends \mvc\model
{
	public function directories($_depth = 0)
	{
		// $myid = $this->login('id');
		$myid        = 190;
		$tmp_result  = $this->sql()->tableAttachments()->whereUser_id($myid)->andAttachment_depth($_depth)
									->orderAttachment_order('ASC')
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
<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait download
{
	/**
	 * find location of file for selected item
	 * then force browser to download file
	 */
	public function get_dl()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'fileManager', 'view', 'notify');

		$qry = $this->qryCreator(['id', 'status', 'field']);
		$qry = $qry->select()->allassoc();
		// $sQry = $sQry->allassoc();
		$qry =  $this->draw_fix($qry);

		foreach ($qry as $key => $row)
		{
			if(isset($row['meta']['file']))
			{
				\lib\utility\file::download(
					$row['meta']['url'],
					$row['meta']['file'],
					$row['meta']['mime']
				);
			}
		}
	}

}
?>
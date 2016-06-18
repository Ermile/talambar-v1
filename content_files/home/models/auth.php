<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait auth
{
	/**
	 * get the selected item and return auth code and address of it
	 * @return [array] auth code and address of it
	 */
	public function post_auth()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'apps', 'view', 'block');

		$qry = $this->qryCreator(['id', 'status']);
		$qry = $qry->select();
		if($qry->num() === 1)
		{
			$qry = $qry->assoc();
			$qry['attachment_meta'] = json_decode($qry['attachment_meta'], true);

			if(isset($qry['attachment_meta']['url']))
			{
				$myAddr = $qry['attachment_meta']['url'];
				$myAddr = $this->url('root'). $myAddr;

				debug::property('addr', $myAddr);
				debug::property('ext',  $qry['attachment_ext']);
			}

			debug::property('authcode', utility\shortURL::encode($qry['id']));
		}
	}

}
?>
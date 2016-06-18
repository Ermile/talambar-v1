<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait favorites
{
	/**
	 * fetch list of favorite items from database and retun it
	 * @return [array] datatable contain list of items
	 */
	public function draw_favorites()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'favorites', 'view', 'block');

		$qry  = $this->qryCreator(['status', 'fav', 'field']);
		$qry = $qry->and('attachment_type', 'file');

		$qry = $qry->select()->allassoc();

		return $this->draw_fix($qry);
	}


	/**
	 * set and uset favorites for selected item
	 * @return [boolean] true if no problem occurs
	 */
	public function post_favorites()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'favorites', 'edit', 'notify');


		$qry         = $this->qryCreator(['id', 'status']);
		$myFavStatus = '#'.utility::post('status');

		$qry         = $qry->set('attachment_fav', $myFavStatus);
		$qry         = $qry->update();

		// commit all changes or rollback and remove file
		// ======================================================
		// you can manage next event with one of these variables,
		// commit for successfull and rollback for failed
		// if query run without error means commit
		$this->commit(function()
		{
			// debug::true(T_("Set Favorites Successfully"));
			debug::property('status', 'ok');
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::title(T_("Error: "));
			debug::property('status', 'fail');
			debug::property('error', T_('Error'));
			// remove file if has problem
		});

	}

}
?>
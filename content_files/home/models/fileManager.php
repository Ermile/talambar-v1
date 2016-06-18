<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait fileManager
{
	/**
	 * fetch list of items from database and retun it
	 * @return [array] datatable contain list of items
	 */
	public function draw()
	{
		$qry       = $this->qryCreator(['location', 'status', 'order', 'field']);
		$qry       = $qry->select();
		$datatable = $qry->allassoc();

		return $this->draw_fix($datatable);
	}


	/**
	 * this function find unique name for items
	 * @param  [type] $_name [description]
	 * @return [type]        [description]
	 */
	public function item_nameChecker($_name)
	{
		$notExist = null;
		$counter  = 0;
		$newName  = $_name;
		$qry   = $this->qryCreator(['location', 'status']);

		// loop until find unique name
		do
		{
			$qry_loop = clone($qry);
			$qry_loop->and('attachment_name', $newName);

			if($qry_loop->select()->num() > 0)
			{
				$counter++;
				$newName = $_name. $counter;
				$notExist = true;
			}
			else
			{
				$notExist = false;
			}

		} while ($notExist);

		return $newName;
	}

	/**
	 * create a new folder
	 * @return [type] if have problem on creating new foldr return false
	 */
	public function post_createfolder()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'fileManager', 'add', 'notify');

		$_location = $this->getLocation();
		if(!$_location)
			return false;

		// var_dump('create new folder');
		// return;
		$value = utility::post('value');
		$value = $this->item_nameChecker($value);
		if(!$value)
			return false;

		$qry = $this->sql();
		$qry = $qry->table('attachments')
					->set('attachment_type',   'folder')
					->set('attachment_addr',   $_location)
					->set('attachment_name',   $value)
					->set('attachment_size',   0)
					->set('attachment_status', 'normal')
					->set('attachment_date',   date('Y-m-d H:i:s'))
					->set('user_id',           $this->login('id'));
		$qry           = $qry->insert();
		// $attachment_id = $qry->LAST_INSERT_ID();

		// commit all changes or rollback and remove file
		// ======================================================
		// you can manage next event with one of these variables,
		// commit for successfull and rollback for failed
		// if query run without error means commit
		$this->commit(function()
		{
			debug::property('status', 'ok');
			debug::true(T_("New folder created Successfully"));
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::true(T_("Error on createding new folder!"));
			debug::property('status', 'fail');
			debug::property('error', T_('Error'));
			// remove file if has problem
		});
	}


	/**
	 * rename the name of selected item
	 * @return [type] if have problem on creating new foldr return false
	 */
	public function post_rename()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'fileManager', 'edit', 'notify');

		$qry   = $this->qryCreator(['id', 'status']);

		$value = utility::post('value');
		$value = $this->item_nameChecker($value);
		if(!$value)
			return false;

		$qry   = $qry->set('attachment_name', $value);
		$qry   = $qry->update();

		// commit all changes or rollback and remove file
		// ======================================================
		// you can manage next event with one of these variables,
		// commit for successfull and rollback for failed
		// if query run without error means commit
		$this->commit(function()
		{
			debug::true(T_("Rename Successfully"));
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


	/**
	 * remove file and folders
	 * @return [type] nothing!
	 */
	public function post_remove()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'fileManager', 'delete', 'notify');

		$qry   = $this->qryCreator(['id']);
		$shift = utility::post('shift');

		if($shift == 'true')
		{
			$qry = $qry->set('attachment_status', 'deleted');
		}
		else
		{
			$qry = $qry->set('attachment_status', '#CASE
				WHEN attachment_status = "normal" THEN "trash"
				WHEN attachment_status = "trash" THEN "deleted"
				END', 'aaa');
		}

		// var_dump($qry->updateString()); exit();
		$qry           = $qry->update();
		// $attachment_id = $qry->LAST_INSERT_ID();

		// commit all changes or rollback and remove file
		// ======================================================
		// you can manage next event with one of these variables,
		// commit for successfull and rollback for failed
		// if query run without error means commit
		$this->commit(function()
		{
			debug::true(T_("Delete Successfully"));
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


	/**
	 * paste file and folders to new location
	 * @return [type] if have problem on creating new foldr return false
	 */
	public function post_paste()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'fileManager', 'edit', 'notify');

		// if type is invalid, return false
		$qry = $this->qryCreator(['id', 'status']);
		$type  = utility::post('type');

		if($type === 'cut')
		{
			$_location = $this->getLocation();
			if(!$_location)
				return false;
			$qry = $qry->set('attachment_addr', $_location);
		}
		elseif($type === 'copy')
		{
			return;
			// INSERT INTO `TABLENAME` (`field2`, `field3`,… ) SELECT `field2`, `field3`,… FROM TABLENAME
		}
		else
			return;

		$qry = $qry->update();

		// commit all changes or rollback and remove file
		// ======================================================
		// you can manage next event with one of these variables,
		// commit for successfull and rollback for failed
		// if query run without error means commit
		$this->commit(function($_type)
		{
			debug::true(T_(ucfirst($_type)). ' '. T_("Successfully"));
			debug::property('status', 'ok');
		}, $type);

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::title(T_("Error: "));
			debug::property('status', 'fail');
			debug::property('error', T_('Error'));
			debug::error(T_('Move Unsuccessful!'));
		});
	}

}
?>
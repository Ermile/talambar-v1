<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait tags
{
	/**
	 * fetch list of items related and return it
	 * @return [array] datatable contain list of items
	 */
	public function draw_tags($_type = false)
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'tags', 'view', 'block');

		// add current user to query string
		$uid       = $this->login('id');
		if(!$uid)
			return false;

		/**
		for Show tags
		first we need to get current user, then join qry with terms and termusages tbl
		 */
		// fetch the list of tags of current user
		if($_type === 'list')
		{
			$qry = $this->sql()->table('terms')
				->where('term_type', 'tag')
				->field('id', '#term_title as title', '#term_url as url');


			$qry->join('termusages')->on('term_id', '#terms.id')
				->field(false)
				->and('termusage_foreign', '#"attachments"');

			$qry->join('attachments')->on('id', '#termusages.termusage_id')
				->field(false)
				->and('user_id', $uid );

			// $qry    = $qry->groupby('term_id', '#ip');
				// ->and('termusage_id', $myId);

			// $qry  = $this->qryCreator(['status', 'fav', 'field']);
			// var_dump($qry->selectString());exit();
			$qry = $qry->select()->allassoc();
			return $qry;

		}
		// fetch the list of items of current user in this tag name
		else
		{
			$myTag = \lib\utility::get('name');

			$qry = $this->sql()->table('terms')
				->where('term_type', 'tag')
				->and('term_title', $myTag)
				->field(false);


			$qry->join('termusages')->on('term_id', '#terms.id')
				->field(false)
				->and('termusage_foreign', '#"attachments"');

			$qry->join('attachments')->on('id', '#termusages.termusage_id')
				->field(
						'id',
						'file_id',
						'#attachment_title as title',
						'#attachment_desc as description',
						'#attachment_type as type',
						// '#attachment_addr as address',
						'#attachment_name as name',
						'#attachment_ext as ext',
						'#attachment_size as size',
						'#attachment_meta as meta',
						'#attachment_parent as parent',
						// '#attachment_order as order',
						'#attachment_fav as fav',
						'#attachment_status as status',
						'#attachment_date as date'
					)
				->and('user_id', $uid );

			// var_dump($qry->selectString());exit();
			$qry = $qry->select()->allassoc();

			// return $qry;
			return $this->draw_fix($qry);
		}
	}


	/**
	 * add new tag to selected item
	 * @return [boolean] if have problem on creating new foldr return false
	 */
	public function post_tagadd()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'tags', 'add', 'notify');

		$myTags  = utility::post('tags');
		$myTags  = explode(',', $myTags);
		foreach ($myTags as $key => $value)
		{
			$value = trim($value," ");
			$value = trim($value,"'");

			if($value)
				$myTags[$key] = $value;
			else
				unset($myTags[$key]);
		}

		$tags_id = array();
		if(count($myTags)>0)
		{
			$qry_tag = $this->sql()->table('terms');
			// add each tag to sql syntax
			foreach ($myTags as $value)
			{
				if($value)
				{
					$qry_tag = $qry_tag
						->set('term_type',  'tag')
						->set('term_title',  $value)
						->set('term_slug',   $value)
						->set('term_url',    $value);
				}
			}
			// var_dump($qry_tag->insertString('IGNORE'));exit();
			$qry_tag->insert('IGNORE');


			// get the list of tags id
			$tags_id = $this->cp_tag_id($myTags, false);
		}

		// ---------------------------------------------- set termusage table
		// if terms exist go to foreach
		if(isset($tags_id) && is_array($tags_id) && count($tags_id)>0)
		{
			$myId = $this->getItems(true);
			if(!is_numeric($myId))
				return false;

			$qry_tagusages = $this->sql()->table('termusages');
			foreach ($tags_id as $value)
				$qry_tagusages = $qry_tagusages
					->set('term_id',           $value)
					->set('termusage_id',      $myId)
					->set('termusage_foreign', 'attachments');
			// var_dump($qry_tagusages->insertString());exit();
			$qry_tagusages->insert('IGNORE');
		}


		$this->commit(function()
		{
			debug::true(T_("Insert Successfully"));
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::title(T_("Transaction error").': ');
		} );
	}


	/**
	 * remove selected tag from selected item
	 * @return [boolean] if have problem on creating new foldr return false
	 */
	public function post_tagremove()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'tags', 'delete', 'notify');

		$myId = $this->getItems(true);
		if(!is_numeric($myId))
			return false;

		$qry_term_del = $this->sql()->table('termusages')
			->where('termusage_id', $myId )
			->and('termusage_foreign', 'attachments');

		$myTags  = utility::post('tags');
		$myTags  = $this->cp_tag_id($myTags);

		if(count(explode(',', $myTags)) === 1)
			$qry_term_del = $qry_term_del->and('term_id', '=', $myTags);
		else
			$qry_term_del = $qry_term_del->and('term_id', 'in', "(". $myTags .")" );

		// var_dump($qry_term_del->deleteString());
		$qry_term_del = $qry_term_del->delete();

		$this->commit(function()
		{
			debug::true(T_("Delete Successfully"));
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::title(T_("Transaction error").': ');
		} );
	}


	/**
	 * send list of tag title and get list of it's id
	 * @param  [type]  $_list   list received by func
	 * @param  boolean $_string type of output
	 * @return [type]           send depending on type of output
	 */
	function cp_tag_id($_list, $_string = true)
	{
		// get the list of tags
		$qry_tags  = $this->sql()->table('terms')->where('term_type', 'tag');
		// $_list     = array_filter($_list);

		if(is_array($_list))
		{
			if(count($_list) === 1)
			{
				// use =
				$qry_tags = $qry_tags->and('term_title', '=', "'".array_pop($_list)."'");
			}
			else
			{
				// use IN
				$_list = implode("','", $_list);
				$_list = "'" . $_list."'";

				$qry_tags = $qry_tags->and('term_title', 'IN', "(". $_list . ")");
			}
		}
		else
		{
			$qry_tags = $qry_tags->and('term_title', '=', "'".$_list."'");
		}

		// set field name and assoc all rows
		// var_dump($qry_tags->field('id')->selectString());
		// var_dump($qry_tags->select()->num());
		// var_dump($qry_tags);
		$qry_tags = $qry_tags->field('id')->select()->allassoc('id');

		if($qry_tags)
		{
			if($_string)
			{
				if(count($qry_tags) === 1 && isset($qry_tags[0]))
					return $qry_tags[0];
				else
					return implode(",", $qry_tags);
			}

			return $qry_tags;
		}
		return null;
	}

}
?>
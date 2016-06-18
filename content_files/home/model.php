<?php
namespace content_files\home;
use \lib\debug;
use \lib\utility;

class model extends \mvc\model
{
	use models\apps;
	use models\auth;
	use models\download;
	use models\favorites;
	use models\fileManager;
	use models\properties;
	use models\search;
	use models\tags;
	use models\upload;

	/**
	 * get the location of file and check it with referer then
	 * if correct pass the complete location contain uid
	 * @return [type] [description]
	 */
	protected function getLocation($_location = false)
	{
		// var_dump($_location);
		if($_location === false)
		{
			$_location = utility::post('location');
			if(!empty($_location) && strpos($_SERVER['HTTP_REFERER'], $_location) === false)
			{
				debug::property('status', 'fail');
				debug::property('error', T_('Fail on get current location'). $_location);

				$this->_processor(['force_json'=>true, 'not_redirect'=>true]);
				return false;
			}
			elseif($_location === null)
			{
				$_location = $this->url('path');
			}
		}

		$uid = $this->login('id');
		$_location = '/'. $uid.'/'. $_location;
		$_location = urldecode($_location);

		return $_location;
	}


	/**
	 * get items send via js then decode and return it
	 * @return [type] return selected elements in explorer
	 */
	protected function getItems($_raw = false)
	{
		$items = utility::post('items');
		if(!$items)
		{
			$items = utility::get('id');
		}

		$items = explode(',', $items);
		if(count($items) < 1)
		{
			return false;
		}

		$myIDs = array();
		foreach($items as $item)
		{
			$myIDs[] = utility\shortURL::decode($item);
		}

		if($_raw)
		{
			return implode($myIDs, ', ');
		}
		// if contain one element pass it in string
		elseif(is_array($_raw) && count($_raw) == 1  &&  isset($myIDs[0]))
		{
			return $myIDs[0];
		}

		return $myIDs;
	}


	/**
	 * create a start of query for current item to use in another functions
	 * @param  [type]  $_need   pass your need in array and we process it
	 * @param  boolean $_id  	pass the id of item manually
	 * @param  boolean $_order  pass the field and type of order
	 * @param  boolean $_status pass status that want it
	 * @return [type]           return the sql object for nex step
	 */
	protected function qryCreator($_need, $_id = false, $_order = false, $_status = false)
	{
		// add current user to query string
		$uid       = $this->login('id');
		if(!$uid)
			return false;



		// --------------------------------------------------------- user_id
		$myQry = $this->sql()
						->table('attachments')
						->where('user_id', $uid);

		// --------------------------------------------------------- attachment_addr
		// add location to query string
		if(in_array('location', $_need))
		{
			// get location
			$myLocation = $this->getLocation();
			if(!$myLocation)
				return false;
			$myQry = $myQry->and('attachment_addr', $myLocation);
		}

		// --------------------------------------------------------- attachment_fav
		// add favorites to query string
		if(in_array('fav', $_need))
		{
			$myQry = $myQry->and('attachment_fav', 1);
		}

		// --------------------------------------------------------- attachment_status
		// add status to query string
		if(in_array('status', $_need))
		{
			if(is_array($_status))
			{
				$_status = implode($_status, ',');
				$myQry = $myQry->and('attachment_status', 'IN', '('& $_status & ')');
			}
			else
			{
				$myQry = $myQry->and('attachment_status', 'IN', "('normal', 'trash')");
			}
		}



		// --------------------------------------------------------- id
		// select with best selector for id
		// different for array and strings
		if(in_array('id', $_need))
		{
			if($_id)
			{
				$myQry = $myQry->and('id', $_id);
			}
			else
			{
				$myId = $this->getItems(true);
				// if only has one id
				if(is_numeric($myId))
				{
					$myQry = $myQry->and('id', $myId);
				}
				// if contain more than one id
				elseif($myId)
				{
					$myQry = $myQry->and('id', 'IN' ,"(".$myId.")");
				}
				// the id is not correct
				else
					return false;
			}
		}


		// --------------------------------------------------------- Search
		// create search query
		if(in_array('search', $_need))
		{
			/**
			Shwo unique result
			 */
			$q = utility::get('q');
			// $myQry = $myQry->and('MATCH(`attachment_name`, `attachment_meta`)', 'AGAINST' ,"('".$q."')");
			$myQry = $myQry->groupOpen('g_search');

			$myQry = $myQry->and("attachment_name", 'LIKE', "'%$q%'");
			$myQry = $myQry->or( "attachment_meta", 'LIKE', "'%$q%'");

			// $myQry = $myQry->groupClose('g_search');


			$myQry->join('attachmentmetas')->on('attachment_id', '#attachments.id')
				// ->groupOpen('g_searchmeta')
				->field(false)
				->and("attachmentmeta_key", 'LIKE', "'%$q%'")
				->or("attachmentmeta_value", 'LIKE', "'%$q%'")
				// ->groupClose('g_searchmeta');
				->groupClose('g_search');


				// ->and('termusage_foreign', '#"attachments"');

		}


		// --------------------------------------------------------- order by type
		// add order to query string if user needif
		if(in_array('order', $_need))
		{
			if(is_array($_order))
			{
				$myQry = $myQry->order('#'. key($_order[0]), $_order[0]);
			}
			else
			{
				$myQry = $myQry->order('#type', 'DESC');
			}
		}

		if(in_array('field', $_need))
		{
			$myQry = $this->qryCreatorField($myQry);
			// $myQry = $myQry->field(
			// 			'id',
			// 			'file_id',
			// 			'#attachment_title as title',
			// 			'#attachment_desc as description',
			// 			'#attachment_type as type',
			// 			// '#attachment_addr as address',
			// 			'#attachment_name as name',
			// 			'#attachment_ext as ext',
			// 			'#attachment_size as size',
			// 			'#attachment_meta as meta',
			// 			'#attachment_parent as parent',
			// 			// '#attachment_order as order',
			// 			'#attachment_fav as fav',
			// 			'#attachment_status as status',
			// 			'#attachment_date as date'
			// 		);
		}

		return $myQry;
	}


	/**
	 * Add field to query string
	 * @param  query string without field
	 * @return query string with added field
	 */
	protected function qryCreatorField($_qry)
	{
		return $_qry->field(
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
					);
	}


	/**
	 * fix datatable for showing it
	 * @param  [type] $_dbtable [give datatable]
	 * @return [type]           [return fixed datatable]
	 */
	public function draw_fix($_dbtable)
	{
		foreach ($_dbtable as $key =>$row)
		{
			if(isset($row['meta']) && $row['meta'])
			{
				$_dbtable[$key]['meta']   = json_decode($row['meta'], true);
			}
			$_dbtable[$key]['cid']    = utility\shortURL::encode($row['id']);
			$_dbtable[$key]['fav']    = $_dbtable[$key]['fav']? 'fa-star': 'fa-star-o';
			$_dbtable[$key]['status'] = $_dbtable[$key]['status'] == 'normal'? '': $_dbtable[$key]['status'];

			// set icon for items
			switch ($row['type'])
			{
				case 'folder':
					$_dbtable[$key]['icon'] = 'folder';
					break;

				case 'file':
					$_dbtable[$key]['icon'] = 'file-o';

					if(isset($_dbtable[$key]['meta']) &&
						isset($_dbtable[$key]['meta']['type']) &&
						$_dbtable[$key]['meta']['type'] !== 'file'
					)
						$_dbtable[$key]['icon'] = 'file-'.$_dbtable[$key]['meta']['type'].'-o';
					break;

				case 'system':
					$_dbtable[$key]['icon'] = 'hdd-o';
					break;

				case 'other':
					$_dbtable[$key]['icon'] = 'file';
					break;

				default:
					$_dbtable[$key]['icon'] = 'file-o';
					break;
			}
		}
		return $_dbtable;
	}

}
?>
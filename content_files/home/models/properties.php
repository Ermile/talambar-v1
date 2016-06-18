<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait properties
{
	/**
	 * add new property to properties of selected file
	 * @param  [type] $_data data for add
	 * @return [boolean] true if no problem occur
	 */
	public function post_propadd($_data)
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'property', 'add', 'notify');

		// add current user to query string
		$uid       = $this->login('id');
		if(!$uid)
			return false;

		if(is_array($_data) && isset($_data['type']) && $_data['type'] === 'auto')
		{
			$myId    = $_data['id'];
			$myType  = $_data['type'];
			$myKey   = $_data['key'];
			$myValue = $_data['value'];
		}
		else
		{
			$myId = $this->getItems(true);
			if(!is_numeric($myId))
				return false;

			$myKey  = utility::post('name');
			$myValue = utility::post('value');
			$myType  = utility::post('type');
			if(!$myType)
				$myType = 'manual';

		}
		// return if name or value is null
		if(strlen($myKey) == 0 || strlen($myValue) == 0)
			return;

		$qry_prop_exist = $this->sql()->table('attachmentmetas')
			->where('attachment_id',     $myId)
			->and('attachmentmeta_cat',  'property_'.$myType)
			->and('attachmentmeta_meta', $uid)
			->and('attachmentmeta_key',  $myKey)
			;



		$prop_exist_count = $qry_prop_exist->select()->num();
		// property exist only one time
		if($prop_exist_count == 1)
		{
			// if exist in table only update value of this item
			$qry_prop_exist = $qry_prop_exist->set('attachmentmeta_value', $myValue);
			// var_dump($qry_prop->updateString());exit();
			$qry_prop_exist->update();
			debug::true(T_("This propery is exist and updated!"));

		}
		// property exist more than one times!
		elseif ($prop_exist_count > 1)
		{
			debug::error(T_("This propery is exist many times!"));
			return false;
		}
		// property does not exist in db
		else
		{
			$qry_prop = $this->sql()->table('attachmentmetas')
				->set('attachment_id',        $myId)
				->set('attachmentmeta_cat',   'property_'.$myType)
				->set('attachmentmeta_meta',  $uid)
				->set('attachmentmeta_key',   $myKey)
				->set('attachmentmeta_value', $myValue);
			// var_dump($qry_prop->insertString());exit();
			$qry_prop->insert();
		}


		$this->commit(function()
		{
			debug::true(T_("Save New Property Successfully"));
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::title(T_("Transaction error").': ');
		} );
	}


	/**
	 * remove selected property from properties of selected item
	 * @return [boolean] if have problem on creating new foldr return false
	 */
	public function post_propremove()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'property', 'delete', 'notify');

		$myId = $this->getItems(true);
		if(!is_numeric($myId))
			return false;

		$myRowID  = utility::post('id');
		if(!$myRowID)
			return;

		$qry_prop = $this->sql()->table('attachmentmetas')
			->where('id',                $myRowID)
			->and('attachment_id',       $myId)
			->and('attachmentmeta_meta', $this->login('id'));

		$qry_prop = $qry_prop->delete();

		$this->commit(function()
		{
			debug::true(T_("Remove Successfully"));
		});

		// if a query has error or any error occour in any part of codes, run roolback
		$this->rollback(function()
		{
			debug::title(T_("Transaction error").': ');
		} );
	}


	/**
	 * get the properties of selected item
	 * @return [json] return a json of properties for show on propbox
	 */
	public function post_prop()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'property', 'view', 'notify');

		$qry   = $this->qryCreator(['id', 'status', 'order']);

		$qry   = $qry->field('id',
							'#attachment_title as title',
							'#attachment_desc as description',
							'#attachment_type as type',
							// '#attachment_addr as address',
							'#attachment_name as name',
							'#attachment_ext as ext',
							'#attachment_size as size',
							'#attachment_parent as parent',
							// '#attachment_order as order',
							'#attachment_status as status',
							'#attachment_date as date',
							'#attachment_meta as meta'
						);
		$datatable = $qry->select();

		if($datatable->num()<1)
			return false;

		$datatable = $datatable->assoc();

		// var_dump($datatable);

		$datatable['meta']   = json_decode($datatable['meta'], true);

		if(isset($datatable['meta']['width']))
			$datatable['width'] = $datatable['meta']['width'];

		if(isset($datatable['meta']['height']))
			$datatable['height'] = $datatable['meta']['height'];

		if(isset($datatable['meta']['thumb']))
			$datatable['thumb'] = $datatable['meta']['thumb'];

		$datatable['size']   = \lib\utility\upload::readableSize($datatable['size'], $datatable['type']);


		$datatable['id']     = utility\shortURL::encode($datatable['id']);
		$datatable['status'] = $datatable['status'] == 'normal'? '': $datatable['status'];

		if($datatable['type'] == 'folder')
		{
			$datatable['icon'] = 'folder';
			unset($datatable['ext']);
		}
		elseif($datatable['type'] == 'file' && isset($datatable['meta']) && isset($datatable['meta']['type']))
		{
			$datatable['icon'] = 'file-'.$datatable['meta']['type'].'-o';
		}
		elseif($datatable['type'] == 'system')
		{
			$datatable['icon'] = 'hdd-o';
		}
		elseif($datatable['type'] == 'other')
		{
			$datatable['icon'] = 'file';
		}
		else
			$datatable['icon'] = 'file-o';

		if(isset($datatable['meta']['file']))
		{
			$datatable['icon'] = 'file-'.$datatable['meta']['type'].'-o';
		}

		switch ($datatable['meta']['mime'])
		{
			case 'audio/ogg':
			case 'audio/mpeg':
			case 'audio/wav':
				$datatable['audio']      = $datatable['meta']['file'];
				$datatable['audio']      = $datatable['meta']['url'];
				$datatable['audio-type'] = $datatable['meta']['mime'];
				break;

			case 'video/mp4':
			case 'video/webm':
			case 'video/ogg':
				$datatable['video']      = $datatable['meta']['file'];
				$datatable['video']      = $datatable['meta']['url'];
				$datatable['video-type'] = $datatable['meta']['mime'];
				break;

			default:
				break;
		}
				// $datatable['video']      = $datatable['meta']['mime'];

		unset($datatable['type']);
		unset($datatable['status']);
		unset($datatable['parent']);
		unset($datatable['meta']);
		unset($datatable['icon']);

		// \lib\utility\upload::readableSize()
		foreach ($datatable as $key => $value)
		{
			if($value === null)
				unset($datatable[$key]);

			// dont translate id
			switch ($key)
			{

				case 'id':
				case 'icon':
				case 'thumb':
				case 'filetype':
				case 'audio':
				case 'audio-type':
				case 'video':
				case 'video-type':
					break;

				case 'title':
				case 'description':
					break;

				case 'size':
				case 'name':
				case 'ext':
				case 'date':
				default:
					unset($datatable[$key]);
					$datatable[T_(ucfirst($key))] = $value;
					break;
			}
		}

		// debug::property('datatable', $datatable);
		// return;


		// ============= add tags to properties
		$myId = $this->getItems(true);
		// if(!is_numeric($myId))
			// return false;

		$qry = $this->sql()->table('terms')
			->where('term_type', 'tag')
			->field('term_title');

		$qry->joinTermusages()->on('term_id', '#terms.id')
			->and('termusage_foreign', '#"attachments"')
			->and('termusage_id', $myId);


		$qry = $qry->select()->allassoc('term_title');
		$qry = $qry? implode($qry, ', ').', ' : null;

		$datatable['tags'] = $qry;


		// ============= add custom prop to properties
		$qry_prop = $this->sql()->table('attachmentmetas')
			->where('attachment_id', $myId )
			->and('attachmentmeta_meta', $this->login('id'))
			->field(
				'id',
				'#attachmentmeta_key as mykey',
				'#attachmentmeta_value as myvalue'
				)
			->select();

		$qry_prop       = $qry_prop->allassoc();
		$datatable_prop = array();
		foreach ($qry_prop as $key => $row)
		{

			$datatable_prop[$row['id']] = [$row['mykey'] => $row['myvalue']];
		}
		$datatable['prop'] = $datatable_prop;


		debug::property('datatable', $datatable);
	}

}
?>
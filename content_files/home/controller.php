<?php
namespace content_files\home;

class controller extends \mvc\controller
{
	/**
	 * handle user requested address and permission
	 * allow user to access to special page
	 * and block unwanted address
	 */
	function _route()
	{
		if(!$this->login())
		{
			\lib\debug::warn(T_("first of all, you must login to system!"));
			$this->redirector(null, false)->set_domain($this->url('AccountService'))->set_url('login?cp=1')->redirect();
			exit();
		}
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', null, null, 'block');

		$myurl   = $this->url('path',-1);


		if(isset($myurl[0]) && $myurl[0] === '$')
		{
			if(count($myurl) === 2 && isset($myurl[1]))
			{
				// $this->route_check_true = true;
				// $tmp_url   = $this->url('path');
				// var_dump($tmp_url);

				switch ($myurl[1])
				{
					case 'upload':
						// $this->model_name = 'content_files\\'.$myurl[1].'\model';
					case 'paste':
					case 'remove':
					case 'createfolder':
					case 'createfile':
					case 'rename':
					case 'prop':
					case 'tagadd':
					case 'tagremove':
					case 'propadd':
					case 'propremove':
					case 'auth':
						// var_dump($myurl[1]);
						// $this->get()->ALL();
						$this->post($myurl[1])->ALL('$/'.$myurl[1]);
						break;
					case 'profile':
					case 'favorites':
					case 'tags':
					case 'search':
					case 'result':
					case 'analytics':
						// $this->route_check_true = true;
						$this->get()->ALL('$/'.$myurl[1]);
						$this->post($myurl[1])->ALL('$/'.$myurl[1]);
						break;
					case 'dl':
						$this->get('dl')->ALL('$/'.$myurl[1]);
						break;

					default:
						\lib\error::bad();
						break;
				}
			}
			else
			{
				\lib\error::bad();
			}
		}
		else
		{
			$this->get()->ALL('/^.*$/');
		}

		// $mymodule = $this->module();

		// show all data on this subdomain
		// $x = $this->post('upload')->ALL('upload');
		// $x = $this->get('killSession')->ALL('killSession');
		// $x = $this->get('resume')->ALL('resume');
		// $this->post('folder')->ALL("folder");


		// please don't comment below line!
		// $this->get()->ALL();
	}

	/**
	 * define perm modules for permission level
	 * @return [array] return the permissions in this content
	 */
	public static function permModules()
	{
		$mylist	= [
					'fileManager' => null,
					'favorites'   => null,
					'tags'        => null,
					'property'    => null,
					'upload'      => ['admin', 'edit', 'delete'],
					'apps'        => ['admin', 'edit', 'delete'],
					'newFolder'   => ['admin', 'view', 'edit', 'delete'],
					'search'      => ['admin', 'add', 'edit', 'delete'],
				];

		// get features value from view and fix it later
		$features = [];
		// if(isset($this->data->feature) && is_array($this->data->feature))
		// 	$features = $this->data->feature;

		foreach ($features as $feature => $enable)
		{
			// if option is not true continue to next
			if(!$enable)
				continue;

			// else switch on enabled feature
			switch ($feature)
			{
				case 'book':
					array_push($mylist, $feature);
					array_push($mylist, 'bookcategories');
					break;

				case 'socialnetworks':
				case 'visitors':
				default:
					array_push($mylist, $feature);
					break;
			}
		}
		// var_dump($mylist);
		// $mylist = $this->model()->permModuleFill($_content, $mylist);
		// var_dump($mylist);

		return $mylist;
	}
}
?>
<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait apps
{
	/**
	 * Save result of custom app as property of file
	 * @return [type] result of saving processs
	 */
	function post_result($_type = 'return')
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'apps', 'add', 'block');

		$appAuthCode = \lib\utility::post('authcode');

		if($appAuthCode)
		{
			$appType = 'post';
		}
		elseif(\lib\utility::get('authcode'))
		{
			$appAuthCode = \lib\utility::get('authcode');
			$appType = 'get';
		}
		else
		{
			return false;
		}

		// check if authcode is correct! then continue else return false
		$myID          = utility\shortURL::decode($appAuthCode);
		$authCodeExist = $this->qryCreator(['id', 'status'], $myID);
		if($authCodeExist->select()->num() <1)
		{
			return false;
		}

		$appResult = \lib\utility::{$appType}('result');

		if($appAuthCode && $appResult)
		{
			$appResult =
			[
				T_('AuthCode')	=> $appAuthCode,
				T_('Result')	=> $appResult,
			];

			for ($i=1; $i <= 5; $i++)
			{
				$appKey   = \lib\utility::{$appType}('key'.$i);
				$appValue = \lib\utility::{$appType}('value'.$i);
				if($appKey && $appValue)
				{
					$appResult[$appKey] = $appValue;
					// run save func for saving result of app in property of file
					if($_type !== 'return')
					{
						$appResult['id']    = $myID;
						$appResult['type']  = 'auto';
						$appResult['key']   = $appKey;
						$appResult['value'] = $appValue;
						$this->post_propadd($appResult);
					}
				}
			}
			if($_type === 'return')
			{
				return $appResult;
			}
		}
		else
			return false;
	}

}
?>
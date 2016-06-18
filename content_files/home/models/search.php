<?php
namespace content_files\home\models;
use \lib\debug;
use \lib\utility;

trait search
{
	/**
	 * Query String of search that search in 2 table attachments and attachmentmetas
	 * and find related data in these tables
	 * @return [array] datatable contain list of item match with search
	 */
	function draw_search()
	{
		// Check permission and if user can do this operation
		// allow to do it, else show related message in notify center
		$this->access('files', 'search', 'view', 'block');

		$uid       = $this->login('id');
		if(!$uid)
			return false;

		$q = utility::get('q');
		// search query string
		$qString = "SELECT DISTINCT
					`attachments`.`id`,
					`attachments`.`file_id`,
					attachment_title 	as title,
					attachment_desc 	as description,
					attachment_type 	as type,
					attachment_name 	as name,
					attachment_ext 		as ext,
					attachment_size 	as size,
					attachment_meta 	as meta,
					attachment_parent 	as parent,
					attachment_fav 		as fav,
					attachment_status 	as status,
					attachment_date 	as date
				FROM `attachments`

				LEFT JOIN `attachmentmetas` ON `attachmentmetas`.`attachment_id` = `attachments`.`id`

				WHERE `attachments`.`user_id` = 1
				AND `attachment_status` IN ('normal', 'trash')
				AND
				(
						`attachment_name` LIKE '%$q%'
					OR 	`attachment_meta` LIKE '%$q%'
					OR 	`attachmentmeta_key` LIKE '%$q%'
					OR 	`attachmentmeta_value` LIKE '%$q%'
				)

				UNION

				SELECT DISTINCT
					`attachments`.`id`,
					`attachments`.`file_id`,
					`attachments`.`attachment_title` 	as title,
					`attachments`.`attachment_desc` 	as description,
					`attachments`.`attachment_type` 	as type,
					`attachments`.`attachment_name` 	as name,
					`attachments`.`attachment_ext` 		as ext,
					`attachments`.`attachment_size` 	as size,
					`attachments`.`attachment_meta` 	as meta,
					`attachments`.`attachment_parent` 	as parent,
					`attachments`.`attachment_fav` 		as fav,
					`attachments`.`attachment_status` 	as status,
					`attachments`.`attachment_date` 	as date
				FROM `terms`

				inner JOIN `termusages` ON `termusages`.`term_id` = `terms`.`id` AND `termusages`.`termusage_foreign` = 'attachments'
				inner JOIN `attachments` ON `attachments`.`id` = `termusages`.`termusage_id` AND `attachments`.`user_id` = $uid
				WHERE
				`terms`.`term_type` = 'tag' AND
				`terms`.`term_title` LIKE '%$q%'
			";
		$sQry = $this->sql()->query($qString);
		$sQry = $sQry->allassoc();

		return $this->draw_fix($sQry);

		// $qry  = $this->qryCreator(['status', 'search', 'field']);
		// var_dump($qry->selectString());exit();
		// $qry = $qry->select()->allassoc();
		// return $this->draw_fix($qry);
	}

}
?>
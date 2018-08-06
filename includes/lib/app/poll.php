<?php
namespace lib\app;

/**
 * Class for poll.
 */
class poll
{

	use poll\add;
	use poll\edit;
	use poll\datalist;
	use poll\dashboard;


	public static function get($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Poll id not set"));
			return false;
		}


		$get = \lib\db\polls::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid poll id"));
			return false;
		}

		$result = self::ready($get);

		return $result;
	}


	/**
	 * check args
	 *
	 * @return     array|boolean  ( description_of_the_return_value )
	 */
	private static function check($_id = null)
	{

		// `user_id`       int(10) UNSIGNED NOT NULL,
		// `title`	        varchar(500) NULL,
		// `lang`	        char(2) NULL,
		// `password`      varchar(200) NULL,
		// `privacy`       enum('public','private') NOT NULL DEFAULT 'public',
		// `status`        enum('draft','publish','expire','deleted','lock','awaiting','block','filter','close', 'full') NOT NULL DEFAULT 'draft',
		// `branding`      bit(1) NULL,
		// `brandingtitle` text CHARACTER SET utf8mb4,
		// `brandingdesc`  text CHARACTER SET utf8mb4,
		// `brandingmeta`  text CHARACTER SET utf8mb4,
		// `redirect`		varchar(2000) NULL,
		// `progresbar`    bit(1) NULL,
		// `trans`         mediumtext CHARACTER SET utf8mb4,
		// `email`      	bit(1) NULL,
		// `emailto` 		varchar(500) CHARACTER SET utf8mb4,
		// `emailtitle` 	varchar(500) CHARACTER SET utf8mb4,
		// `emailmsg`  	text CHARACTER SET utf8mb4,
		// `wellcometitle`    text CHARACTER SET utf8mb4,
		// `wellcomedesc`     text CHARACTER SET utf8mb4,
		// `wellcomemedia`    text CHARACTER SET utf8mb4,
		// `thankyoutitle`    text CHARACTER SET utf8mb4,
		// `thankyoudesc`   text CHARACTER SET utf8mb4,
		// `thankyoumedia` text CHARACTER SET utf8mb4,

		$title = \dash\app::request('title');
		$title = trim($title);
		if(!$title)
		{
			\dash\notif::error(T_("Please fill the poll title"), 'title');
			return false;
		}

		if(mb_strlen($title) > 150)
		{
			\dash\notif::error(T_("Please fill the poll title less than 150 character"), 'title');
			return false;
		}

		if($_id)
		{
			$load_old = \lib\db\polls::get(['id' => $_id, 'limit' => 1]);

		}

		$status = \dash\app::request('status');
		if($status && !in_array($status, ['enable', 'disable']))
		{
			\dash\notif::error(T_("Invalid status of poll"), 'status');
			return false;
		}

		$signup = \dash\app::request('signup');
		$signup = $signup ? 1 : null;


		$desc = \dash\app::request('desc');
		$desc = trim($desc);
		if($desc && mb_strlen($desc) > 500)
		{
			\dash\notif::error(T_("Description must be less than 500 character"), 'desc');
			return false;
		}

		$args           = [];
		$args['title']  = $title;
		$args['status'] = $status;
		$args['desc']   = $desc;
		$args['signup'] = $signup;

		return $args;
	}


	/**
	 * ready data of poll to load in api
	 *
	 * @param      <type>  $_data  The data
	 */
	public static function ready($_data)
	{
		$result = [];
		foreach ($_data as $key => $value)
		{

			switch ($key)
			{
				case 'id':
				case 'user_id':
					$result[$key] = \dash\coding::encode($value);
					break;

				default:
					$result[$key] = $value;
					break;
			}
		}

		return $result;
	}

}
?>
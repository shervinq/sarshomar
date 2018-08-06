<?php
namespace lib\app\poll;

trait add
{

	/**
	 * add new poll
	 *
	 * @param      array          $_args  The arguments
	 *
	 * @return     array|boolean  ( description_of_the_return_value )
	 */
	public static function add($_args = [])
	{
		\dash\app::variable($_args);

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("User not found"), 'user');
			return false;
		}

		// check args
		$args = self::check();

		if($args === false || !\dash\engine\process::status())
		{
			return false;
		}

		$return = [];

		$args['user_id'] = \dash\user::id();

		if(!$args['status'])
		{
			$args['status']  = 'draft';
		}

		$poll_id = \lib\db\polls::insert($args);

		if(!$poll_id)
		{
			\dash\notif::error(T_("No way to insert poll"), 'db');
			return false;
		}

		if(\dash\engine\process::status())
		{
			\dash\log::db('addNewPlll', ['data' => $poll_id, 'datalink' => \dash\coding::encode($poll_id)]);
			\dash\notif::ok(T_("Poll successfuly added"));
		}

		return $return;
	}

}
?>
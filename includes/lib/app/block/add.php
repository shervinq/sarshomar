<?php
namespace lib\app\block;

trait add
{

	/**
	 * add new block
	 *
	 * @param      array          $_args  The arguments
	 *
	 * @return     array|boolean  ( description_of_the_return_value )
	 */
	public static function add($_args = [])
	{
		\dash\app::variable($_args, ['raw_field' => self::$raw_field]);

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

		if(!$args['status'])
		{
			$args['status']  = 'draft';
		}

		$return = [];

		$block_id = \lib\db\blocks::insert($args);

		if(!$block_id)
		{
			\dash\notif::error(T_("No way to insert block"), 'db');
			return false;
		}

		if(\dash\engine\process::status())
		{
			\dash\log::db('addNewPoll', ['data' => $block_id, 'datalink' => \dash\coding::encode($block_id)]);
			\dash\notif::ok(T_("Block successfuly added"));
		}

		$return['id'] = \dash\coding::encode($block_id);

		return $return;
	}

}
?>
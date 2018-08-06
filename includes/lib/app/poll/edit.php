<?php
namespace lib\app\poll;

trait edit
{
	/**
	 * edit a poll
	 *
	 * @param      <type>   $_args  The arguments
	 *
	 * @return     boolean  ( description_of_the_return_value )
	 */
	public static function edit($_args, $_id)
	{
		\dash\app::variable($_args);

		$result = self::get($_id);

		if(!$result)
		{
			return false;
		}

		$id = \dash\coding::decode($_id);

		$args = self::check($id);

		if($args === false || !\dash\engine\process::status())
		{
			return false;
		}


		if(!empty($args))
		{
			$update = \lib\db\polls::update($args, $id);

			if(\dash\engine\process::status())
			{
				\dash\log::db('editPoll', ['data' => $id, 'datalink' => \dash\coding::encode($id)]);
				\dash\notif::ok(T_("Poll successfully updated"));
			}
		}
	}
}
?>
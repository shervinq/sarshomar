<?php
namespace lib\app\question;

trait edit
{
	/**
	 * edit a question
	 *
	 * @param      <type>   $_args  The arguments
	 *
	 * @return     boolean  ( description_of_the_return_value )
	 */
	public static function edit($_args, $_id)
	{
		\dash\app::variable($_args, ['raw_field' => self::$raw_field]);

		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

		$args = self::check($id);

		if($args === false || !\dash\engine\process::status())
		{
			return false;
		}

		unset($args['poll_id']);

		if(!\dash\app::isset_request('title')) 		unset($args['title']);
		if(!\dash\app::isset_request('desc')) 		unset($args['desc']);
		if(!\dash\app::isset_request('media')) 		unset($args['media']);
		if(!\dash\app::isset_request('require')) 	unset($args['require']);
		if(!\dash\app::isset_request('type')) 		unset($args['type']);
		if(!\dash\app::isset_request('maxchar')) 	unset($args['maxchar']);
		if(!\dash\app::isset_request('sort')) 		unset($args['sort']);
		if(!\dash\app::isset_request('status')) 	unset($args['status']);

		if(!empty($args))
		{
			$update = \lib\db\questions::update($args, $id);

			if(\dash\engine\process::status())
			{
				\dash\log::db('editBlock', ['data' => $id, 'datalink' => \dash\coding::encode($id)]);
				\dash\notif::ok(T_("Question successfully updated"));
			}
		}
	}
}
?>
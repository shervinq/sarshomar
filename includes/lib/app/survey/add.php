<?php
namespace lib\app\survey;

trait add
{

	/**
	 * add new survey
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

		$return = [];


		if(!isset($args['title']))
		{
			\dash\notif::error(T_("Please fill the survey title"), 'title');
			return false;
		}

		$args['user_id'] = \dash\user::id();

		if(!$args['status'])
		{
			$args['status']  = 'draft';
		}

		if(!$args['lang'])
		{
			$args['lang']  = \dash\language::current();
		}

		if(!$args['privacy'])
		{
			$args['privacy']  = 'public';
		}

		$survey_id = \lib\db\surveys::insert($args);

		if(!$survey_id)
		{
			\dash\notif::error(T_("No way to insert survey"), 'db');
			return false;
		}

		if(\dash\engine\process::status())
		{
			\dash\log::set('addNewSurvay', ['data' => $survey_id, 'datalink' => \dash\coding::encode($survey_id)]);
			\dash\notif::ok(T_("Survay successfuly added"));
		}

		$return['id'] = \dash\coding::encode($survey_id);

		return $return;
	}

}
?>
<?php
namespace lib\app\question;

trait add
{

	/**
	 * add new question
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

		$survey_id = \dash\coding::decode(\dash\app::request('survey_id'));
		$args['sort'] = intval(\lib\db\questions::get_count(['survey_id' => $survey_id])) + 1;

		$return = [];

		$question_id = \lib\db\questions::insert($args);

		if(!$question_id)
		{
			\dash\notif::error(T_("No way to insert question"), 'db');
			return false;
		}

		\lib\db\surveys::update_countblock($survey_id);

		if(\dash\engine\process::status())
		{
			\dash\log::db('addNewSurvay', ['data' => $question_id, 'datalink' => \dash\coding::encode($question_id)]);
			\dash\notif::ok(T_("Question successfuly added"));
		}

		$return['id'] = \dash\coding::encode($question_id);

		return $return;
	}

}
?>
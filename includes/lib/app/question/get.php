<?php
namespace lib\app\question;

/**
 * Class for question.
 */
trait get
{

	public static function get_by_step($_survey_id, $_step)
	{
		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"), 'survey_id');
			return false;
		}

		if(!is_numeric($_step))
		{
			\dash\notif::error(T_("Invalid step number"), 'step');
			return false;
		}

		$_step = intval($_step);

		$load = \lib\db\questions::get(['survey_id' => $survey_id, 'sort' => $_step, 'status' => [' != ', " 'deleted' "], 'limit' => 1]);
		if(is_array($load))
		{
			$load = self::ready($load);
		}
		return $load;

	}

	public static function sort_choice($_args)
	{
		\dash\app::variable($_args);
		$sort = \dash\app::request('sort');
		if(!$sort || !is_array($sort))
		{
			\dash\notif::error(T_("No valid sort method sended!"));
			return false;
		}


		$survey_id = \dash\app::request('survey_id');
		$survey_id = \dash\coding::decode($survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"), 'survey_id');
			return false;
		}

		$load_survey = \lib\db\surveys::get(['id' => $survey_id, 'limit' => 1]);
		if(!$load_survey || !isset($load_survey['user_id']))
		{
			\dash\notif::error(T_("Invalid survey id"), 'survey_id');
			return false;
		}

		if(intval(\dash\user::id()) !== intval($load_survey['user_id']))
		{
			if(!\dash\permission::supervisor())
			{
				\dash\log::db('isNotYourSurvay', ['data' => $survey_id]);
				\dash\notif::error(T_("This is not your survey!"), 'survey_id');
				return false;
			}
		}

		$block_survey = \lib\app\question::block_survey(\dash\app::request('survey_id'));

		if(count($block_survey) !== count($sort))
		{
			\dash\notif::error(T_("Some question was lost!"));
			return false;
		}

		$old_sort = array_column($block_survey, 'id');

		if($old_sort !== $sort)
		{
			$block_survey = array_combine($old_sort, $block_survey);

			$new_bloc_sort = [];
			foreach ($sort as $key => $value)
			{
				if(isset($block_survey[$value]))
				{
					$id = $block_survey[$value]['id'];
					$id = \dash\coding::decode($id);
					$new_bloc_sort[$key] = $id;
				}
				else
				{
					\dash\notif::error(T_("some data is incorrect!"));
					return false;
				}
			}

			\lib\db\questions::save_sort($new_bloc_sort);

		}

		\dash\notif::ok(T_("Sort question saved"));
		return true;

	}


	public static function get($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}


		$get = \lib\db\questions::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid question id"));
			return false;
		}

		$result = self::ready($get);

		return $result;
	}


	public static function block_survey($_survey_id)
	{
		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}

		$result = \lib\db\questions::get_sort(['survey_id' => $survey_id, 'status' => [" != ", " 'deleted' "] ]);

		if(is_array($result))
		{
			$result = array_map(['self', 'ready'], $result);
		}

		return $result;
	}
}
?>
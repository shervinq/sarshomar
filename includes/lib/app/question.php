<?php
namespace lib\app;

/**
 * Class for question.
 */
class question
{

	use question\add;
	use question\edit;
	use question\datalist;
	use question\dashboard;
	use question\type;
	use question\next;


	public static $raw_field =
	[
		'media',
		'setting',
		'choice',
	];

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

		$load = \lib\db\questions::get(['survey_id' => $survey_id, 'sort' => $_step, 'limit' => 1]);
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

		$result = \lib\db\questions::get_sort(['survey_id' => $survey_id]);

		if(is_array($result))
		{
			$result = array_map(['self', 'ready'], $result);
		}

		return $result;
	}


	/**
	 * check args
	 *
	 * @return     array|boolean  ( description_of_the_return_value )
	 */
	private static function check($_id = null)
	{
		$args            = [];

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

		if($_id)
		{
			$load_question = \lib\db\questions::get(['id' => $_id, 'survey_id' => $survey_id, 'limit' => 1]);
			if(!$load_question)
			{
				\dash\notif::error(T_("Invalid questions id"), 'survey_id');
				return false;
			}
		}

		$title   = \dash\app::request('title');
		$desc    = \dash\app::request('desc');
		$media   = \dash\app::request('media');
		$require = \dash\app::request('require') ? 1 : null;


		$type = \dash\app::request('type');
		if($type && !self::get_type($type))
		{
			\dash\notif::error(T_("Invalid question type"), 'type');
			return false;
		}

		if(\dash\app::isset_request('type') && !$type)
		{
			\dash\notif::error(T_("Type of question can not be null"), 'type');
			return false;
		}

		$maxchar = \dash\app::request('maxchar');
		if($maxchar && !is_numeric($maxchar))
		{
			\dash\notif::error(T_("Please fill maxchar as a number"), 'maxchar');
			return false;
		}

		if($maxchar)
		{
			$maxchar = abs(intval($maxchar));
			if($maxchar > 1E+9)
			{
				\dash\notif::error(T_("Min is out of range"), 'maxchar');
				return false;
			}
		}


		$min = \dash\app::request('min');
		if($min && !is_numeric($min))
		{
			\dash\notif::error(T_("Please fill min as a number"), 'min');
			return false;
		}

		if($min)
		{
			$min = abs(intval($min));
			if($min > 1E+9)
			{
				\dash\notif::error(T_("Min is out of range"), 'min');
				return false;
			}
		}

		$max = \dash\app::request('max');
		if($max && !is_numeric($max))
		{
			\dash\notif::error(T_("Please fill max as a number"), 'max');
			return false;
		}

		if($max)
		{
			$max = abs(intval($max));
			if($max > 1E+9)
			{
				\dash\notif::error(T_("Max is out of range"), 'max');
				return false;
			}
		}

		$sort = \dash\app::request('sort');
		if($sort && !is_numeric($sort))
		{
			\dash\notif::error(T_("Please fill the sort as a number"), 'sort');
			return false;
		}

		if($sort)
		{
			$sort = abs(intval($sort));
			if($sort > 1E+9)
			{
				\dash\notif::error(T_("Maxchart is out of range"), 'sort');
				return false;
			}
		}

		$status = \dash\app::request('status');
		if($status && !in_array($status, ['draft','publish','expire','deleted','lock','awaiting','question','filter','close', 'full']))
		{
			\dash\notif::error(T_("Invalid status of question"), 'status');
			return false;
		}

		$choice_sort = \dash\app::request('choice_sort');
		if($choice_sort && !in_array($choice_sort, ['save','random','asc','desc',]))
		{
			\dash\notif::error(T_("Invalid choice sort of question"), 'choice_sort');
			return false;
		}



		if(is_array($media))
		{
			$media = json_encode($media, JSON_UNESCAPED_UNICODE);
		}

		$remove_choice = \dash\app::request('remove_choice');
		$add_choice    = \dash\app::request('add_choice');

		$choicetitle  = \dash\app::request('choicetitle');

		if($choicetitle && mb_strlen($choicetitle) > 10000)
		{
			$choicetitle = substr($choicetitle, 0, 10000);
		}

		$choicefile          = \dash\app::request('choicefile');

		if(\dash\app::isset_request('choicetitle') && $choicetitle !== '0' && !$choicetitle && !$choicefile)
		{
			\dash\notif::error(T_("Please fill the choice title"), 'choicetitle');
			return false;
		}

		$old_choice = [];


		if($add_choice || $remove_choice)
		{
			if(isset($load_question['choice']))
			{
				$old_choice = json_decode($load_question['choice'], true);
			}

			if(!is_array($old_choice))
			{
				$old_choice = [];
			}

			if($remove_choice)
			{
				$choice_key = \dash\app::request('choice_key');
				if(array_key_exists($choice_key, $old_choice))
				{
					unset($old_choice[$choice_key]);
				}
				else
				{
					\dash\notif::error(T_("Invalid choice key for remove"));
					return false;
				}
			}
			else
			{
				$new_choice          = [];
				$new_choice['title'] = $choicetitle;

				if($choicefile)
				{
					$new_choice['file'] = $choicefile;
				}

				$old_choice[] = $new_choice;
			}

			$choice         = json_encode($old_choice, JSON_UNESCAPED_UNICODE);
			$args['choice'] = $choice;
		}

		if(
			\dash\app::isset_request('random') ||
			\dash\app::isset_request('otherchoice') ||
			\dash\app::isset_request('min') ||
			\dash\app::isset_request('max') ||
			\dash\app::isset_request('placeholder') ||
			\dash\app::isset_request('maxchoice') ||
			\dash\app::isset_request('minchoice') ||
			\dash\app::isset_request('maxrate') ||
			\dash\app::isset_request('choicehelp') ||
			\dash\app::isset_request('choiceinline')

		  )
		{

			$setting                 = [];
			$setting['random']       = \dash\app::request('random') ? true : false;
			$maxrate = \dash\app::request('maxrate');
			if($maxrate && (intval($maxrate) > 10 || intval($maxrate) < 0))
			{
				\dash\notif::error(T_("Please set maximum rate between 0 and 10"), 'maxrate');
				return false;
			}
			$setting['maxrate']      = $maxrate;

			// @check value
			$setting['minchoice']    = \dash\app::request('minchoice');
			$setting['maxchoice']    = \dash\app::request('maxchoice');

			$myType = isset($load_question['type']) ? $load_question['type'] : null;

			if(!$myType && $type)
			{
				$myType = $type;
			}

			if($min)
			{
				$min = abs(intval($min));
			}

			if($max)
			{
				$max = abs(intval($max));
			}

			if($myType && self::get_type($myType, 'rangenumber'))
			{
				$range = intval(self::get_type($myType, 'rangenumber'));

				if(!$min && !$max)
				{
					$min = 0;
					$max = $range;
				}
				elseif($min && $max)
				{
					if($max - $min > $range)
					{
						$max = $min + $range;
					}
				}
				elseif(!$min && $max)
				{
					$min = $max - $range;
					if($min < 0)
					{
						$min = 0;
					}
				}
				elseif($min && !$max)
				{
					$max = $min + $range;
				}
			}

			$setting['min']          = $min;
			$setting['max']          = $max;


			$setting['choiceinline'] = \dash\app::request('choiceinline') ? true : false;

			$setting['choice_sort']  = $choice_sort;
			$setting['otherchoice']  = \dash\app::request('otherchoice') ? true : false;
			$placeholder             = \dash\app::request('placeholder');
			if($placeholder && mb_strlen($placeholder) > 10000)
			{
				$placeholder = substr($placeholder, 0, 10000);
			}

			if($placeholder)
			{
				$placeholder = \dash\safe::remove_nl($placeholder);
			}

			$choicehelp             = \dash\app::request('choicehelp');
			if($choicehelp && mb_strlen($choicehelp) > 10000)
			{
				$choicehelp = substr($choicehelp, 0, 10000);
			}

			if($choicehelp)
			{
				$choicehelp = \dash\safe::remove_nl($choicehelp);
			}

			$setting['placeholder'] = $placeholder;
			$setting['choicehelp'] = $choicehelp;

			$args['setting'] = json_encode($setting, JSON_UNESCAPED_UNICODE);
		}

		$args['survey_id'] = $survey_id;
		$args['title']   = $title;
		$args['desc']    = $desc;
		$args['media']   = $media;
		$args['require'] = $require;
		$args['type']    = $type;
		$args['maxchar'] = $maxchar;
		$args['sort']    = $sort;
		$args['status']  = $status;

		return $args;
	}


	/**
	 * ready data of question to load in api
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
				case 'status':
					continue;
					break;

				case 'id':
				case 'user_id':
					$result[$key] = \dash\coding::encode($value);
					break;
				case 'type':
					$result[$key] = $value;
					$result['type_detail'] = self::get_type($value);
					break;

				case 'media':
				case 'choice':
				case 'setting':
					$result[$key] = json_decode($value, true);
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
<?php
namespace lib\app;

/**
 * Class for answer.
 */
class answer
{
	use \lib\app\answer\datalist;
	use \lib\app\answer\get;

	public static function dateNow()
	{
		return date("Y-m-d H:i:s");
	}


	public static function my_answer($_survey_id, $_question_id)
	{
		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to conitinue"));
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}

		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			\dash\notif::error(T_("Question id not set"));
			return false;
		}

		$get_answer =
		[
			'answerdetails.question_id' => $question_id,
			'answerdetails.user_id'     => \dash\user::id(),
			'answerdetails.survey_id'   => $survey_id,
			'limit'                     => 1,
		];

		$old_answer_detail = \lib\db\answerdetails::get_join($get_answer);

		if(!$old_answer_detail)
		{
			return null;
		}

		return $old_answer_detail;


	}

	public static function add($_survey_id, $_step, $_args)
	{
		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to conitinue"));
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}

		$survey_detail = \lib\db\surveys::get(['id' => $survey_id, 'limit' => 1]);

		if(!$survey_detail)
		{
			\dash\notif::error(T_("Invalid survey id"));
			return false;
		}

		if(!$_step || !is_numeric($_step))
		{
			\dash\notif::error(T_("Question step not set"));
			return false;
		}

		$_step = intval($_step);

		$question_detail = \lib\db\questions::get(['sort' => $_step, 'survey_id' => $survey_id, 'limit' => 1]);

		if(!$question_detail || !isset($question_detail['id']))
		{
			\dash\notif::error(T_("Invalid question id"));
			return false;
		}

		$question_id = $question_detail['id'];

		$question_detail = \lib\app\question::ready($question_detail);

		\dash\app::variable($_args);

		$answer = \dash\app::request('answer');
		$skip   = \dash\app::request('skip') ? true : false;
		if($skip)
		{
			$answer = null;
		}

		if(!$skip)
		{
			$validation = self::answer_validate($question_detail, $answer);
			if(!$validation)
			{
				\dash\notif::error(T_("Invalid your answer"), 'answer');
				return false;
			}

			$validation_min_max = self::answer_validate_min_max($question_detail, $answer);
			if(!$validation_min_max)
			{
				\dash\notif::error(T_("Your answer is out of range"), 'answer');
				return false;
			}
		}

		$require = self::check_require($question_detail, $answer, $skip);
		if(!$require)
		{
			\dash\notif::error(T_("Please fill this field to continue"), 'answer');
			return false;
		}

		$answer_term_id = null;

		$multiple_choice = false;

		if(!$skip)
		{
			if(is_array($answer))
			{
				$multiple_choice = true;
			}

			if(($answer || $answer === '0') && !$multiple_choice)
			{
				$answer_term_id = \lib\db\answerterms::get_id($answer, $question_detail['type']);
				if(!$answer_term_id)
				{
					\dash\notif::error(T_("No way to inset your answer"));
					return false;
				}
			}
		}

		$load_old_answer =
		[
			'user_id'   => \dash\user::id(),
			'survey_id' => $survey_id,
			'limit'     => 1,
		];

		$load_old_answer = \lib\db\answers::get($load_old_answer);

		if(!$load_old_answer)
		{
			$insert_answer =
			[
				'user_id'      => \dash\user::id(),
				'survey_id'    => $survey_id,
				'startdate'    => self::dateNow(),
				'step'         => $_step,
				'lastquestion' => $question_id,
				'status'       => 'start',
				'ref'          => null,
				'skip'         => $skip   ? 1 : null,
				'skiptry'      => $skip   ? 1 : null,
				'answer'       => $answer && !$skip ? 1 : null,
				'answertry'    => $answer && !$skip ? 1 : null,
			];
			$answer_id = \lib\db\answers::insert($insert_answer);
		}
		else
		{
			$answer_id       = $load_old_answer['id'];

			$answer_count    = (isset($load_old_answer['answer']) && $load_old_answer['answer'])            ? intval($load_old_answer['answer'])    	: 0;
			$skip_count      = (isset($load_old_answer['skip']) && $load_old_answer['skip'])           		? intval($load_old_answer['skip'])      	: 0;
			$answertry_count = (isset($load_old_answer['answertry']) && $load_old_answer['answertry']) 		? intval($load_old_answer['answertry']) 	: 0;
			$skiptry_count   = (isset($load_old_answer['skiptry']) && $load_old_answer['skiptry'])     		? intval($load_old_answer['skiptry'])   	: 0;
			$countblock      = (isset($survey_detail['countblock']) && $survey_detail['countblock'])    ? intval($survey_detail['countblock'])    : 0;

			$update_answer = [];

			if($skip)
			{
				$update_answer['skip']    = $skip_count + 1;
				$update_answer['skiptry'] = $skiptry_count + 1;
			}

			if($answer)
			{
				$update_answer['answer']    = $answer_count + 1;
				$update_answer['answertry'] = $answertry_count + 1;
			}

			$update_answer['step']         = $_step;
			$update_answer['lastquestion'] = $question_id;
			$update_answer['lastmodified'] = self::dateNow();

			if(intval($_step) === intval($countblock))
			{
				$update_answer['complete'] = 1;
				$update_answer['enddate']  = self::dateNow();
			}

			\lib\db\answers::update($update_answer, $answer_id);
		}

		$old_answer_detail_args =
		[
			'user_id'     => \dash\user::id(),
			'survey_id'   => $survey_id,
			'answer_id'   => $answer_id,
			'question_id' => $question_id,
		];

		$time_key = 'dateview_'. (string) $survey_id. '_'. (string) $_step;
		$dateview = \dash\session::get($time_key) && is_string(\dash\session::get($time_key)) ? \dash\session::get($time_key) : self::dateNow();

		if(!$multiple_choice || $skip)
		{
			$old_answer_detail_args['limit'] = 1;
			$old_answer_detail = \lib\db\answerdetails::get($old_answer_detail_args);
			if(isset($old_answer_detail['id']))
			{
				$update_answer_detail =
				[
					'answerterm_id' => $answer_term_id,
					'skip'          => $skip ? 1 : null,
					'dateanswer'    => self::dateNow(),
				];

				\lib\db\answerdetails::update($update_answer_detail, $old_answer_detail['id']);
			}
			else
			{
				// @chekc telegram have not url module!!

				$insert_answer_detail =
				[
					'user_id'       => \dash\user::id(),
					'survey_id'     => $survey_id,
					'answer_id'     => $answer_id,
					'question_id'   => $question_id,
					'answerterm_id' => $answer_term_id,
					'skip'          => $skip ? 1 : null,
					'dateview'      => $dateview,
					'dateanswer'    => self::dateNow(),
				];

				\lib\db\answerdetails::insert($insert_answer_detail);
			}
		}
		else
		{
			// mutli choise mode
			$old_answer_detail = \lib\db\answerdetails::get($old_answer_detail_args);
			if($old_answer_detail)
			{
				\lib\db\answerdetails::delete_where($old_answer_detail_args);
			}

			// insert new answer detail
			$multi_insert = [];
			foreach ($answer as $key => $value)
			{
				$answer_term_id = \lib\db\answerterms::get_id($value, $question_detail['type']);
				$multi_insert[] =
				[
					'user_id'       => \dash\user::id(),
					'survey_id'     => $survey_id,
					'answer_id'     => $answer_id,
					'question_id'   => $question_id,
					'answerterm_id' => $answer_term_id,
					'dateview'      => $dateview,
					'dateanswer'    => self::dateNow(),
				];
			}

			if(!empty($multi_insert))
			{
				\lib\db\answerdetails::multi_insert($multi_insert);
			}
		}

		// \dash\notif::ok(T_("Your answer was saved"));
		return true;

	}


	public static function check_require($_question_detail, $_answer, $_skip = false)
	{
		// check is require
		if(isset($_question_detail['require']) && $_question_detail['require'])
		{
			if((!$_answer && $_answer !== '0') || (is_array($_answer) && empty($_answer)))
			{
				if(isset($_question_detail['type']) && $_question_detail['type'] === 'confirm')
				{
					return true;
				}

				if(!$_skip)
				{
					return false;
				}
			}
		}
		return true;
	}

	public static function answer_validate_min_max($_question_detail, $_answer)
	{

		$maxchar = 10000;
		$min     = 0;
		$max     = 999999999;
		if(isset($_question_detail['maxchar']))
		{
			$maxchar = intval($_question_detail['maxchar']);
		}

		if(isset($_question_detail['setting']['min']))
		{
			$min = intval($_question_detail['setting']['min']);
		}

		if(isset($_question_detail['setting']['max']))
		{
			$max = intval($_question_detail['setting']['max']);
		}

		$valid = true;
		switch ($_question_detail['type'])
		{
			case 'short_answer':
			case 'descriptive_answer':
			case 'email':
			case 'website':
				if(mb_strlen($_answer) > $maxchar)
				{
					$valid = false;
				}
				break;

			case 'numeric':
				if(intval($_answer) < $min || intval($_answer) > $max)
				{
					$valid = false;
				}
				break;

			case 'single_choice':
			case 'multiple_choice':
			case 'dropdown':
			case 'card_descign':
			case 'confirm':
			case 'date':
			case 'rating':
			case 'star':
				break;

		}

		return $valid;
	}


	public static function answer_validate($_question_detail, $_answer)
	{
		$maxchar = 10000;
		$min     = 0;
		$max     = 999999999;
		if(isset($_question_detail['maxchar']))
		{
			$maxchar = intval($_question_detail['maxchar']);
		}

		if(isset($_question_detail['setting']['min']))
		{
			$min = intval($_question_detail['setting']['min']);
		}

		if(isset($_question_detail['setting']['max']))
		{
			$max = intval($_question_detail['setting']['max']);
		}

		$valid = true;
		switch ($_question_detail['type'])
		{
			case 'short_answer':
				// no thing
				break;

			case 'descriptive_answer':
				// no thing
				break;

			case 'numeric':
				if(!is_numeric($_answer))
				{
					$valid = false;
				}
				break;

			case 'single_choice':
				if(isset($_question_detail['choice']) && is_array($_question_detail['choice']))
				{
					$choice_title = array_column($_question_detail['choice'], 'title');

					if(!in_array($_answer, $choice_title))
					{
						$valid = false;
					}
				}
				break;

			case 'multiple_choice':
			case 'dropdown':
				if(is_array($_answer) && isset($_question_detail['choice']) && is_array($_question_detail['choice']))
				{
					$choice_title = array_column($_question_detail['choice'], 'title');
					foreach ($_answer as $key => $value)
					{
						if(!in_array($value, $choice_title))
						{
							$valid = false;
						}
					}
				}
				break;


			case 'card_descign':
				break;

			case 'confirm':
				break;

			case 'date':
				//
				break;

			case 'email':
				break;

			case 'website':
				break;

			case 'rating':
				break;

			case 'star':
				break;

		}

		return $valid;
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
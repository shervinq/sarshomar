<?php
namespace lib\app;

/**
 * Class for answer.
 */
class answer
{

	public static function add($_survey_id, $_question_id, $_args)
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

		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			\dash\notif::error(T_("Question id not set"));
			return false;
		}

		$question_detail = \lib\db\questions::get(['id' => $question_id, 'survey_id' => $survey_id, 'limit' => 1]);

		if(!$question_detail)
		{
			\dash\notif::error(T_("Invalid question id"));
			return false;
		}

		$question_detail = \lib\app\question::ready($question_detail);

		\dash\app::variable($_args);

		$answer = \dash\app::request('answer');

		$require = self::check_require($question_detail, $answer);
		if(!$require)
		{
			\dash\notif::error(T_("Please fill this field to continue"), 'answer');
			return false;
		}

		$validation = self::answer_validate($question_detail, $answer);
		if(!$validation)
		{
			\dash\notif::error(T_("Invalid your answer"), 'answer');
			return false;
		}

		$answer_term_id = null;
		if($answer || $answer === '0')
		{
			$answer_term_id = \lib\db\answerterms::get_id($answer, $question_detail['type']);
			if(!$answer_term_id)
			{
				\dash\notif::error(T_("No way to inset your answer"));
				return false;
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
				'user_id'   => \dash\user::id(),
				'survey_id' => $survey_id,
				'startdate' => date("Y-m-d H:i:s"),
				'step'      => $question_id,
				'status'    => 'start',
				'ref'       => null,
				'skip'      => 0,
				'skiptry'   => 0,
				'answer'    => 1,
				'answertry' => 1,
			];
			$answer_id = \lib\db\answers::insert($insert_answer);
		}
		else
		{
			$answer_id       = $load_old_answer['id'];
			$answer_count    = isset($load_old_answer['answer']) ? intval($load_old_answer['answer']) : 0;
			$skip_count      = isset($load_old_skip['skip']) ? 	intval($load_old_skip['skip']) : 0;
			$answertry_count = isset($load_old_answertry['answertry']) ? intval($load_old_answertry['answertry']) : 0;
			$skiptry_count   = isset($load_old_skiptry['skiptry']) ? intval($load_old_skiptry['skiptry']) : 0;

			$update_answer =
			[
				'step'      => $question_id,
				'status'    => 'early',
				'skip'      => $skiptry_count + 1,
				'skiptry'   => $skiptry_count + 1,
				'answer'    => $answer_count + 1,
				'answertry' => $answertry_count + 1,
			];
			\lib\db\answers::update($update_answer, $answer_id);
		}

		$old_answer_detail =
		[
			'user_id'     => \dash\user::id(),
			'survey_id'   => $survey_id,
			'answer_id'   => $answer_id,
			'question_id' => $question_id,
			'limit'       => 1,
		];

		$old_answer_detail = \lib\db\answerdetails::get($old_answer_detail);
		if(isset($old_answer_detail['id']))
		{
			$update_answer_detail =
			[
				'answerterm_id' => $answer_term_id,
				'skip'          => null,
				'dateanswer'    => date("Y-m-d H:i:s"),
			];

			\lib\db\answerdetails::update($update_answer_detail, $old_answer_detail['id']);
		}
		else
		{

			$insert_answer_detail =
			[
				'user_id'       => \dash\user::id(),
				'survey_id'     => $survey_id,
				'answer_id'     => $answer_id,
				'question_id'   => $question_id,
				'answerterm_id' => $answer_term_id,
				'skip'          => null,
				'dateview'      => date("Y-m-d H:i:s"),
				'dateanswer'    => date("Y-m-d H:i:s"),
			];

			\lib\db\answerdetails::insert($insert_answer_detail);
		}

		\dash\notif::ok(T_("Your answer was saved"));
		return true;

	}


	public static function check_require($_question_detail, $_answer)
	{
		// check is require
		if(isset($_question_detail['require']) && $_question_detail['require'])
		{
			if(!$_answer && $_answer !== '0')
			{
				return false;
			}
		}
		return true;
	}


	public static function answer_validate($_question_detail, $_answer)
	{

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
				break;

			case 'multiple_choice':
				break;

			case 'dropdown':
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
}
?>
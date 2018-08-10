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
			$skip_count      = isset($load_old_skip['skip']) ? intval($load_old_skip['skip']) : 0;
			$answertry_count = isset($load_old_answertry['answertry']) ? intval($load_old_answertry['answertry']) : 0;
			$skiptry_count   = isset($load_old_skiptry['skiptry']) ? intval($load_old_skiptry['skiptry']) : 0;

// `startdate`     datetime NULL,
// `enddate`       datetime NULL,
// `lastmodified`  datetime NULL,
// `status`        enum('start','early','middle','late','complete','skip','spam','filter','block') NULL DEFAULT NULL,
// `step`     		bigint(20) UNSIGNED NULL,
// `ref`	        varchar(1000) NULL,
// `complete`	    bit(1) NULL,
// `skip`     		int(10) UNSIGNED NULL,
// `skiptry`   	int(10) UNSIGNED NULL,
// `answer`   		int(10) UNSIGNED NULL,
// `answertry`   	int(10) UNSIGNED NULL,

// andswer  detail
// `user_id`       int(10) UNSIGNED NOT NULL,
// `survey_id`     bigint(20) UNSIGNED NOT NULL,
// `answer_id`     bigint(20) UNSIGNED NOT NULL,
// `question_id`   bigint(20) UNSIGNED NOT NULL,
// `answerterm_id` bigint(20) UNSIGNED NULL,
// `skip`    		bit(1) NULL,
// `dateview`      datetime NULL,
// `dateanswer`    datetime NULL,
			$update_answer =
			[
				'step'      => $question_id,
				'skip'      => $skiptry_count + 1,
				'skiptry'   => $skiptry_count + 1,
				'answer'    => $answer_count + 1,
				'answertry' => $answertry_count + 1,
			];
		}

		var_dump($answer);
		var_dump($question_detail);

		var_dump($survey_detail);exit();
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
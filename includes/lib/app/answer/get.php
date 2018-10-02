<?php
namespace lib\app\answer;

trait get
{

	public static function export_all($_survey_id)
	{
		$load_survery = \lib\app\survey::get($_survey_id);
		if(!$load_survery)
		{
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		$question  = \lib\db\questions::get(['survey_id' => $survey_id]);
		$answer    = \lib\db\answerdetails::get_join(['answerdetails.survey_id' => $survey_id], ['for_export' => true]);

		if(!is_array($question))
		{
			$question = [];
		}

		$question = array_combine(array_column($question, 'id'), $question);

		$question_key = array_keys($question);
		$question_key = array_flip($question_key);
		$question_key = array_map(function(){return null;}, $question_key);

		$result = [];

		if(!is_array($answer))
		{
			$answer = [];
		}

		foreach ($answer as $key => $value)
		{
			if(!isset($result[$value['user_id']]))
			{
				$result[$value['user_id']]          = $question_key;
				$result[$value['user_id']]['start'] = $value['startdate'] ? \dash\datetime::fit(strtotime($value['startdate']), 'full') : null;
				$result[$value['user_id']]['end']   = $value['enddate'] ? \dash\datetime::fit(strtotime($value['enddate']), 'full') : null;
			}

			if(!isset($result[$value['user_id']][$value['question_id']]))
			{
				$result[$value['user_id']][$value['question_id']] = null;
			}

			$result[$value['user_id']][$value['question_id']] = $value['text'];
		}

		$final = [];

		foreach ($result as $key => $value)
		{
			foreach ($value as $my_question_id => $text)
			{
				if(isset($question[$my_question_id]['title']))
				{
					$final[$key][$question[$my_question_id]['title']] = $text;
				}
				else
				{
					$final[$key][$my_question_id] = $text;
				}
			}
		}

		\dash\utility\export::csv([ 'name' => 'export_answer', 'data' => $final]);
	}

	public static function get_result_table($_survey_id , $_question_id)
	{
		if(!\dash\user::id())
		{
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Invalid survey id"));
			return false;
		}

		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			\dash\notif::error(T_("Invalid answer id"));
			return false;
		}

		$chart_result = \lib\db\answers::get_result_table($survey_id, $question_id, \dash\user::id());

		$new = [];
		if(is_array($chart_result))
		{
			foreach ($chart_result as $key => $value)
			{
				if(isset($value['text']) && isset($value['count']))
				{
					$new[] =
					[
						'key'   => $value['text'],
						'value' => $value['count'],
					];
				}
			}
		}

		if(!$new)
		{
			$new = null;
		}

		$new = json_encode($new, JSON_UNESCAPED_UNICODE);
		return $new;

	}


	public static function get_result($_survey_id , $_question_id)
	{
		if(!\dash\user::id())
		{
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Invalid survey id"));
			return false;
		}

		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			\dash\notif::error(T_("Invalid answer id"));
			return false;
		}

		$chart_result = \lib\db\answers::get_chart($survey_id, $question_id, \dash\user::id());

		$new = [];
		if(is_array($chart_result))
		{
			foreach ($chart_result as $key => $value)
			{
				if(isset($value['text']) && isset($value['count']))
				{
					$new[] =
					[
						'key'   => $value['text'],
						'value' => intval($value['count']),
					];
				}
			}
		}
		$hi_chart               = [];
		$hi_chart['categories'] = json_encode(array_column($new, 'key'), JSON_UNESCAPED_UNICODE);
		$hi_chart['value']      = json_encode(array_column($new, 'value'), JSON_UNESCAPED_UNICODE);

		return $hi_chart;
	}


	public static function get_user_answer($_survey_id , $_answer_id)
	{
		if(!\dash\user::id())
		{
			return false;
		}

		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			\dash\notif::error(T_("Invalid survey id"));
			return false;
		}

		$answer_id = \dash\coding::decode($_answer_id);
		if(!$answer_id)
		{
			\dash\notif::error(T_("Invalid answer id"));
			return false;
		}

		$get_args =
		[
			'answerdetails.answer_id' => $answer_id,
			'answerdetails.survey_id' => $survey_id,
			'surveys.user_id'         => \dash\user::id(),
		];

		if(\dash\permission::supervisor())
		{
			unset($get_args['surveys.user_id']);
		}

		$result = \lib\db\answerdetails::get_join($get_args);

		$temp              = [];

		foreach ($result as $key => $value)
		{
			$check = self::ready($value);
			if($check)
			{
				$temp[] = $check;
			}
		}

		return $temp;
	}
}
?>
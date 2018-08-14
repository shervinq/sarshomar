<?php
namespace lib\app\answer;

trait get
{
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
<?php
namespace lib\app\answer;

trait get
{
	public static function get_result($_survey_id , $_answer_id)
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
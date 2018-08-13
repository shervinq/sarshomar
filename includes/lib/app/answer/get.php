<?php
namespace lib\app\answer;

trait get
{

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

		$result = \lib\db\answerdetails::get_join(['answerdetails.answer_id' => $answer_id, 'answerdetails.survey_id' => $survey_id]);

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
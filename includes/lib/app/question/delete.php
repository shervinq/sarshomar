<?php
namespace lib\app\question;


trait delete
{

	public static function delete($_survey_id, $_question_id)
	{
		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			return false;
		}

		$question_id = \dash\coding::decode($_question_id);
		if(!$question_id)
		{
			return false;
		}

		if(!\dash\user::id())
		{
			return false;
		}

		$check_valid = \lib\db\questions::is_my_question($survey_id, $question_id, \dash\user::id());
		if(!$check_valid)
		{
			\dash\notif::error(T_("This is nout you question"));
			return false;
		}

		$check_answer = \lib\db\answerdetails::get(['question_id' => $question_id, 'limit' => 1]);
		if($check_answer)
		{
			\lib\db\questions::update(['status' => 'deleted'], $question_id);
		}
		else
		{
			\lib\db\questions::delete($question_id);
		}

		\lib\db\surveys::update_countblock($survey_id);

		return true;
	}
}
?>
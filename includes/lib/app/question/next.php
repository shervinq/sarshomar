<?php
namespace lib\app\question;

trait next
{
	/**
	 * edit a question
	 *
	 * @param      <type>   $_args  The arguments
	 *
	 * @return     boolean  ( description_of_the_return_value )
	 */
	public static function next($_survey_id)
	{
		$survey_id = \dash\coding::decode($_survey_id);
		if(!$survey_id)
		{
			return false;
		}

		$first = \lib\db\questions::get_sort(['survey_id' => $survey_id, 'limit' => 1]);
		if(is_array($first))
		{
			$first = self::ready($first);
		}
		return $first;
	}


	public static function next_url($_survey_id, $_question_id)
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

		$next = \lib\db\questions::get_next($survey_id, $question_id);
		var_dump($next);
	}
}
?>
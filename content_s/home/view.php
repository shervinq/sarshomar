<?php
namespace content_s\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Survey"));
		$page_title = \dash\data::surveyRow_title();
		if($page_title)
		{
			\dash\data::page_title($page_title);
		}

		\dash\data::page_desc(T_("Description of survey"));
		// $page_desc = \dash\data::surveyRow_desc();
		// if($page_desc)
		// {
		// 	\dash\data::page_desc($page_desc);
		// }

		$survey = \dash\data::surveyRow();

		$step = 'start';

		if(isset($survey['wellcometitle']) || isset($survey['wellcomedesc']) || isset($survey['wellcomemedia']['file']))
		{
			$step = 'wellcome';
			$next_question = \lib\app\question::next(\dash\url::module());

			\dash\data::nextQuestion($next_question);
		}

		$step_sort = \dash\request::get('step');

		if($step_sort)
		{
			$question = \lib\app\question::get_by_step(\dash\url::module(), $step_sort);
			if(!$question || !isset($question['type']))
			{
				\dash\header::status(404, T_("Invalid question id"));
			}
			\dash\data::question($question);
			$step = $question['type'];

		}

		\dash\data::step($step);
	}
}
?>
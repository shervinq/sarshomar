<?php
namespace content_a\question;


class view
{
	public static function load_question()
	{
		\content_a\survey\view::load_survey();

		$id = \dash\request::get('questionid');
		if($id)
		{
			$load = \lib\app\question::get($id);
			if(!$load)
			{
				\dash\header::status(404, T_("Invalid question id"));
			}

			if(isset($load['type']))
			{
				\dash\data::choiceDetail(\lib\app\question::get_type($load['type']));
			}

			\dash\data::dataRow($load);

			return $load;
		}
	}
}
?>

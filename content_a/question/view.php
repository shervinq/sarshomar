<?php
namespace content_a\question;


class view
{
	public static function load_question()
	{
		$id = \dash\request::get('questionid');
		$load = \lib\app\question::get($id);
		if(!$load)
		{
			\dash\header::status(404, T_("Invalid question id"));
		}

		\dash\data::dataRow($load);

		return $load;
	}
}
?>

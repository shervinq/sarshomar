<?php
namespace content_a\survey;


class view
{
	public static function load_survey()
	{
		$id = \dash\request::get('id');
		$load = \lib\app\survey::get($id);
		if(!$load)
		{
			\dash\header::status(404, T_("Invalid survey id"));
		}

		\dash\data::surveyRow($load);

		return $load;
	}
}
?>

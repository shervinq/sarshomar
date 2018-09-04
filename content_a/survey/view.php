<?php
namespace content_a\survey;


class view
{
	public static function load_survey()
	{
		$id = \dash\request::get('id');
		$load = \lib\app\survey::get($id);

		if(!$load || !isset($load['user_id']))
		{
			\dash\header::status(404, T_("Invalid survey id"));
		}

		if(intval(\dash\coding::decode($load['user_id'])) !== intval(\dash\user::id()))
		{
			if(!\dash\permission::supervisor())
			{
				\dash\header::status(403, T_("This is not your survey"));
			}
		}

		\dash\data::surveyRow($load);

		return $load;
	}
}
?>

<?php
namespace content_a\survey\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('sun');
		\dash\data::page_title(T_("Survay Dashboard"));
		\dash\data::page_desc(T_("Check your survey detail and monitor them"));

		if(\dash\request::get('id'))
		{
			$id        = \dash\request::get('id');
			$load_survey = \lib\app\survey::get($id);
			if(!$load_survey)
			{
				\dash\header::status(404, T_("Invalid survey id"));
			}
			\dash\data::surveyRow($load_survey);

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());

			\dash\data::badge_link(\dash\url::here());
			\dash\data::badge_text(T_('Back to survey list'));
		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

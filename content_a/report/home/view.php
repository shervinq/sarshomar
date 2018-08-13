<?php
namespace content_a\report\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('chart');
		\dash\data::page_title(T_("Report list"));
		\dash\data::page_desc(T_("Check your survey report"));

		if(\dash\request::get('id'))
		{
			\dash\data::badge_link(\dash\url::here(). '/survey?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to survey dashboard'));

			$id        = \dash\request::get('id');
			$load_survey = \lib\app\survey::get($id);
			if(!$load_survey)
			{
				\dash\header::status(404, T_("Invalid survey id"));
			}
			\dash\data::dataRow($load_survey);

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::dataRow_title());


		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

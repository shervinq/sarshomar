<?php
namespace content_a\question\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('dzone');
		\dash\data::page_title(T_("Question list"));
		\dash\data::page_desc(T_("Check your survey question list"));

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

			$dataTable = \lib\app\question::block_survey($id);

			if(!$dataTable)
			{
				\dash\redirect::to(\dash\url::this(). '/add?new=1&id='. \dash\request::get('id'));
			}

			\dash\data::dataTable($dataTable);

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

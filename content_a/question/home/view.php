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

			\content_a\survey\view::load_survey();

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());

			$id = \dash\request::get('id');

			$dataTable = \lib\app\question::block_survey($id);

			\dash\data::dataTable($dataTable);

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

<?php
namespace content_a\sort\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('refresh');
		\dash\data::page_title(T_("Change question order"));
		\dash\data::page_desc(T_("Check your survey question list and sort it"));

		if(\dash\request::get('id'))
		{
			\dash\data::badge_link(\dash\url::here(). '/survey?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to survey dashboard'));

			\content_a\survey\view::load_survey();

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());

			$dataTable = \lib\app\question::block_survey(\dash\request::get('id'));

			if(!$dataTable)
			{
				\dash\redirect::to(\dash\url::here(). '/question/add?new=1&id='. \dash\request::get('id'));
			}

			\dash\data::questionList($dataTable);
		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

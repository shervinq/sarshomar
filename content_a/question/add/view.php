<?php
namespace content_a\question\add;


class view
{
	public static function config()
	{
		if(!\dash\request::get('id'))
		{
			\dash\redirect::to(\dash\url::here());
		}

		\dash\data::page_pictogram('plus');

		\dash\data::page_title(T_("Add new question"));
		\dash\data::page_desc(T_("add new question by some data and can edit it later"));

		if(\dash\request::get('new'))
		{
			\dash\data::badge_link(\dash\url::here(). '/poll?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to poll dashboard'));
		}
		else
		{
			\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to question list'));
		}

		\dash\data::allType(\lib\app\question::all_type());
	}
}
?>

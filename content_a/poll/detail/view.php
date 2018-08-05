<?php
namespace content_a\festival\detail;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\dash\data::display_festivalAdd('content_a/festival/layout.html');

		\dash\data::page_title(\dash\data::dataRow_title(). ' | '. T_("Edit festival detail"));
		\dash\data::page_desc(T_("Edit festival detail like intro, about, award ..."));

		\dash\data::badge_link(\dash\url::here(). '/festival?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to festival dashboard'));

		if(\dash\request::get('type') && !in_array(\dash\request::get('type'), ['intro','about','target','axis','view','place','award']))
		{
			\dash\header::status(404, T_("Invalid type"));
		}

	}
}
?>

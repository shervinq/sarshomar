<?php
namespace content_a\festival\add;


class view
{
	public static function config()
	{
		\dash\permission::access('fpFestivalAdd');

		\dash\data::page_pictogram('plus');

		\dash\data::display_festivalAdd('content_a/festival/layout.html');

		\dash\data::page_title(T_("Add new festival"));
		\dash\data::page_desc(T_("add new festival by some data and can edit it later"));
		\dash\data::badge_link(\dash\url::here(). '/festival');
		\dash\data::badge_text(T_('Back to festival list'));

	}
}
?>

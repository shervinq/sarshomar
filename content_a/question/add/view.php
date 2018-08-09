<?php
namespace content_a\poll\add;


class view
{
	public static function config()
	{

		\dash\data::page_pictogram('plus');

		\dash\data::page_title(T_("Add new poll"));
		\dash\data::page_desc(T_("add new poll by some data and can edit it later"));
		\dash\data::badge_link(\dash\url::this());
		\dash\data::badge_text(T_('Back to poll list'));

	}
}
?>

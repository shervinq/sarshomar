<?php
namespace content_a\block\add;


class view
{
	public static function config()
	{

		\dash\data::page_pictogram('plus');

		\dash\data::page_title(T_("Add new block"));
		\dash\data::page_desc(T_("add new block by some data and can edit it later"));
		\dash\data::badge_link(\dash\url::this());
		\dash\data::badge_text(T_('Back to block list'));

	}
}
?>

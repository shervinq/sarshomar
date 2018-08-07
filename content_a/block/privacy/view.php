<?php
namespace content_a\block\privacy;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\content_a\block\view::load();

		\dash\data::page_title(T_("Edit block"). ' | '. \dash\data::dataRow_title());

		\dash\data::page_desc(T_("You can edit your block detail"));

		\dash\data::badge_link(\dash\url::this());
		\dash\data::badge_text(T_('Back to block list'));

	}
}
?>

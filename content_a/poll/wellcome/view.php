<?php
namespace content_a\poll\wellcome;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\content_a\poll\view::load();

		\dash\data::page_title(T_("Edit poll"). ' | '. \dash\data::dataRow_title());

		\dash\data::page_desc(T_("You can edit your poll detail"));

		\dash\data::badge_link(\dash\url::this());
		\dash\data::badge_text(T_('Back to poll list'));

	}
}
?>

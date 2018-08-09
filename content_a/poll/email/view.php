<?php
namespace content_a\poll\email;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\content_a\poll\view::load_poll();

		\dash\data::page_title(T_("Edit poll"). ' | '. \dash\data::pollRow_title());

		\dash\data::page_desc(T_("You can edit your poll detail"));

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to poll dashboard'));

	}
}
?>

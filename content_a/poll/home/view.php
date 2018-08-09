<?php
namespace content_a\poll\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('sun');
		\dash\data::page_title(T_("Poll Dashboard"));
		\dash\data::page_desc(T_("Check your poll detail and monitor them"));

		if(\dash\request::get('id'))
		{
			$id        = \dash\request::get('id');
			$load_poll = \lib\app\poll::get($id);
			if(!$load_poll)
			{
				\dash\header::status(404, T_("Invalid poll id"));
			}
			\dash\data::pollRow($load_poll);

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::pollRow_title());

			\dash\data::badge_link(\dash\url::here());
			\dash\data::badge_text(T_('Back to poll list'));
		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

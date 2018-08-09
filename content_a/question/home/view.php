<?php
namespace content_a\question\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('dzone');
		\dash\data::page_title(T_("Question list"));
		\dash\data::page_desc(T_("Check your poll question list"));

		if(\dash\request::get('id'))
		{
			\dash\data::badge_link(\dash\url::here(). '/poll?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to poll dashboard'));

			$id        = \dash\request::get('id');
			$load_poll = \lib\app\poll::get($id);
			if(!$load_poll)
			{
				\dash\header::status(404, T_("Invalid poll id"));
			}
			\dash\data::dataRow($load_poll);

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::dataRow_title());

			$dataTable = \lib\app\block::block_poll($id);

			if(!$dataTable)
			{
				\dash\redirect::to(\dash\url::this(). '/add?new=1&id='. \dash\request::get('id'));
			}

			\dash\data::dataTable($dataTable);

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

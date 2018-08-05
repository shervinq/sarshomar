<?php
namespace content_a\festival\home;


class view
{
	public static function config()
	{
		\dash\permission::access('fpFestivalView');

		\dash\data::display_festivalDisplay('content_a/festival/home/list.html');
		\dash\data::page_pictogram('magic');

		if(\dash\request::get('id'))
		{
			$id            = \dash\request::get('id');
			$load_festival = \lib\app\festival::get($id);
			if(!$load_festival)
			{
				\dash\header::status(403, T_("Invalid festival id"));
			}
			\dash\data::dataRow($load_festival);
			\dash\data::display_festivalDisplay('content_a/festival/home/dashboard.html');
			\dash\data::page_title(T_("Festivals"). ' | '. \dash\data::dataRow_title());
			\dash\data::page_desc(T_("check festival detail"));

			\dash\data::badge_link(\dash\url::here(). '/festival');
			\dash\data::badge_text(T_('Back to festival list'));

		}
		else
		{

			\dash\data::page_title(T_("Festivals list"));
			\dash\data::page_desc(T_("check last festival and add or edit a festival"));

			$args               = [];
			$args['order']      = 'DESC';
			$args['pagenation'] = false;

			$dataTable          = \lib\app\festival::list(null, $args);

			\dash\data::dataTable($dataTable);
		}
	}
}
?>

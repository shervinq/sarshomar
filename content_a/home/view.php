<?php
namespace content_a\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Dashboard"));

		\dash\data::page_title(T_("Survay list"));
		\dash\data::page_desc(T_("check last survey and add or edit a survey"));

		$arg               = [];
		$arg['user_id']    = \dash\user::id();
		$arg['pagenation'] = false;
		$dataTable         = \lib\app\survey::list(null, $arg);
		\dash\data::dataTable($dataTable);

	}
}
?>
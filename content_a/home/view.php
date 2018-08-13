<?php
namespace content_a\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Dashboard"));

		\dash\data::page_title(T_("Questionnaires"));
		\dash\data::page_desc(T_("Manage all of your surveys and easily add new one or manage exisiting."));

		$arg               = [];
		$arg['user_id']    = \dash\user::id();
		$arg['pagenation'] = false;
		$dataTable         = \lib\app\survey::list(null, $arg);
		\dash\data::dataTable($dataTable);

	}
}
?>
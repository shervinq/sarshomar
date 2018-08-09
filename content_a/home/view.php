<?php
namespace content_a\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Dashboard"));

		\dash\data::page_title(T_("Poll list"));
		\dash\data::page_desc(T_("check last poll and add or edit a poll"));

		$arg               = [];
		$arg['user_id']    = \dash\user::id();
		$arg['pagenation'] = false;
		$dataTable         = \lib\app\poll::list(null, $arg);
		\dash\data::dataTable($dataTable);

	}
}
?>
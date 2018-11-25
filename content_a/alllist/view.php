<?php
namespace content_a\alllist;


class view
{
	public static function config()
	{
		if(!\dash\permission::supervisor())
		{
			\dash\header::status(403);
		}

		\dash\data::page_title(T_("Questionnaires"));
		\dash\data::page_desc(T_("Manage all of your surveys and easily add new one or manage exisiting."));
		\dash\data::page_pictogram('tachometer');

		$arg               = [];
		$arg['limit'] = 25;
		$q = \dash\request::get('q');
		$dataTable         = \lib\app\survey::list($q, $arg);
		\dash\data::dataTable($dataTable);

	}
}
?>
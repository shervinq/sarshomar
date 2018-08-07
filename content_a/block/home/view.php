<?php
namespace content_a\block\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('magic');
		\dash\data::page_title(T_("Block list"));
		\dash\data::page_desc(T_("check last block and add or edit a block"));

		$arg = [];
		$arg['user_id'] = \dash\user::id();
		$dataTable = \lib\app\block::list(null, $arg);
		\dash\data::dataTable($dataTable);
	}
}
?>

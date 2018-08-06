<?php
namespace content_a\poll\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('magic');
		\dash\data::page_title(T_("Poll list"));
		\dash\data::page_desc(T_("check last poll and add or edit a poll"));
	}
}
?>

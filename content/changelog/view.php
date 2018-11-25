<?php
namespace content\changelog;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Sarshomar's change log"));
		\dash\data::page_desc(T_('We were born to do Best!'). ' ' . T_("We are Developers, please wait!"));
		\dash\data::page_special(true);
	}
}
?>
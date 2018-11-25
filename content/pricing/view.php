<?php
namespace content\pricing;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Pricing"));
		\dash\data::page_desc(T_('Simple and clean pricing.'));
	}
}
?>
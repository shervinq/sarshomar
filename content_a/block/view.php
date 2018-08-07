<?php
namespace content_a\block;


class view
{
	public static function load()
	{
		$id = \dash\request::get('id');
		$load = \lib\app\block::get($id);
		if(!$load)
		{
			\dash\header::status(404, T_("Invalid block id"));
		}

		\dash\data::dataRow($load);

		return $load;
	}
}
?>

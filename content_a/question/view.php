<?php
namespace content_a\poll;


class view
{
	public static function load_poll()
	{
		$id = \dash\request::get('id');
		$load = \lib\app\poll::get($id);
		if(!$load)
		{
			\dash\header::status(404, T_("Invalid poll id"));
		}

		\dash\data::dataRow($load);

		return $load;
	}
}
?>

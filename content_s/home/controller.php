<?php
namespace content_s\home;

class controller
{
	public static function routing()
	{
		$module = \dash\url::module();

		// \lib\app\tg\survey::get($module, \dash\request::get('step'));

		\lib\app\survey::fire($module, true);
		\dash\open::get();
		\dash\open::post();
	}


}
?>
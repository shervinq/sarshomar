<?php
namespace content_s\home;

class controller
{
	public static function routing()
	{
		$module = \dash\url::module();

		// \lib\app\tg\survey::get($module, \dash\request::get('step'));

		\lib\app\survey::fire($module, true);

		$child = \dash\url::child();

		if($child && !in_array($child, ['ex']))
		{
			\dash\header::status(404);
		}

		\dash\open::get();
		\dash\open::post();
	}


}
?>
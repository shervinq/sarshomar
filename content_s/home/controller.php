<?php
namespace content_s\home;

class controller
{
	public static function routing()
	{
		$module = \dash\url::module();
		\lib\app\survey::fire($module, true);
		\dash\open::get();
		\dash\open::post();
	}


}
?>
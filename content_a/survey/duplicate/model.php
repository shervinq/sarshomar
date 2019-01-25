<?php
namespace content_a\survey\duplicate;


class model
{
	public static function post()
	{
		if(\dash\request::post('duplicate'))
		{
			$result = \lib\app\survey::duplicate(\dash\request::get('id'));

			if(\dash\engine\process::status())
			{
				\dash\redirect::pwd();
			}
		}
	}
}
?>

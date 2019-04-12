<?php
namespace content_a\survey\security;


class model
{
	public static function post()
	{
		$post                = [];
		$post['template']       = \dash\request::post('template');


		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

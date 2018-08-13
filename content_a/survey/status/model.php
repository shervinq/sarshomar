<?php
namespace content_a\survey\status;


class model
{
	public static function post()
	{
		$post           = [];

		$post['status'] = \dash\request::post('status');

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

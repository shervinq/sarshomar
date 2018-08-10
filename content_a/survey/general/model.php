<?php
namespace content_a\survey\general;


class model
{
	public static function post()
	{
		$post           = [];
		$post['title']  = \dash\request::post('title');
		$post['status'] = \dash\request::post('status');

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

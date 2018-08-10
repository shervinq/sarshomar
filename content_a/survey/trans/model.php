<?php
namespace content_a\survey\trans;


class model
{
	public static function post()
	{
		$post           = [];
		$post['trans']  = \dash\request::post('trans');


		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

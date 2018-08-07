<?php
namespace content_a\poll\trans;


class model
{
	public static function post()
	{
		$post           = [];
		$post['trans']  = \dash\request::post('trans');


		$result = \lib\app\poll::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

<?php
namespace content_a\question\choise;


class model
{
	public static function post()
	{
		$post            = [];
		$post['type']    = \dash\request::post('type');
		$post['poll_id'] = \dash\request::get('id');

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

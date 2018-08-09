<?php
namespace content_a\question\general;


class model
{
	public static function post()
	{
		$post            = [];
		$post['title']   = \dash\request::post('title');
		$post['desc']    = \dash\request::post('desc');
		$post['poll_id'] = \dash\request::get('id');

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

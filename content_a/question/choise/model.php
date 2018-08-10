<?php
namespace content_a\question\choise;


class model
{
	public static function post()
	{
		$post                = [];
		$post['poll_id']     = \dash\request::get('id');
		$post['choisetitle'] = \dash\request::post('choisetitle');

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

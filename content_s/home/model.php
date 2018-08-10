<?php
namespace content_s\home;


class model
{
	public static function post()
	{
		$post             = [];
		$post['answer']   = \dash\request::post('answer');
		$result           = \lib\app\answer::add(\dash\url::module(), \dash\request::get('q'), $post);


	}
}
?>
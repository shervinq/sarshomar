<?php
namespace content_s\home;


class model
{
	public static function post()
	{
		$post             = [];
		$post['answer']   = \dash\request::post('answer');
		$result           = \lib\app\answer::add(\dash\url::module(), \dash\request::get('q'), $post);

		if(!$result)
		{
			return false;
		}

		$next_url = \lib\app\question::next_url(\dash\url::module(), \dash\request::get('q'));
		var_dump($next_url);exit();

	}
}
?>
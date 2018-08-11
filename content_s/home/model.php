<?php
namespace content_s\home;


class model
{
	public static function post()
	{
		$post             = [];
		$post['answer']   = \dash\request::post('answer');
		$result           = \lib\app\answer::add(\dash\url::module(), \dash\request::get('step'), $post);

		if(!$result)
		{
			return false;
		}

		$step = \dash\request::get('step');
		$step = intval($step) + 1;
		$query = ['step' => $step];

		\dash\redirect::to(\dash\url::this().'?'. http_build_query($query));

	}
}
?>
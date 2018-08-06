<?php
namespace content_a\poll\general;


class model
{
	public static function post()
	{
		$post           = [];
		$post['title']  = \dash\request::post('title');
		$post['status'] = \dash\request::post('status');

		$result = \lib\app\poll::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			if(isset($result['id']))
			{
				\dash\redirect::to(\dash\url::this(). '/general?id='. $result['id']);
			}
			else
			{
				\dash\redirect::to(\dash\url::this());
			}

		}
	}
}
?>

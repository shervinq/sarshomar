<?php
namespace content_a\festival\add;


class model
{
	public static function post()
	{
		\dash\permission::access('fpFestivalAdd');

		$post             = [];
		$post['title']    = \dash\request::post('title');
		$post['subtitle'] = \dash\request::post('subtitle');
		$post['slug']     = \dash\request::post('slug');
		$post['language'] = \dash\language::current();
		$post['status']   = 'draft';

		$result = \lib\app\festival::add($post);

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

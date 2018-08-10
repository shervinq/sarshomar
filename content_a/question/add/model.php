<?php
namespace content_a\question\add;


class model
{
	public static function post()
	{
		$post            = [];
		$post['type']    = \dash\request::post('type');
		$post['survey_id'] = \dash\request::get('id');

		$result = \lib\app\question::add($post);

		if(\dash\engine\process::status())
		{
			if(isset($result['id']))
			{
				\dash\redirect::to(\dash\url::this(). '/title?id='. \dash\request::get('id'). '&questionid='. $result['id']);
			}
			else
			{
				\dash\redirect::to(\dash\url::this());
			}

		}
	}
}
?>

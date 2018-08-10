<?php
namespace content_a\question\type;


class model
{
	public static function post()
	{
		$post            = [];
		$post['type']    = \dash\request::post('type');
		$post['survey_id'] = \dash\request::get('id');

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

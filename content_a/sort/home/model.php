<?php
namespace content_a\sort\home;


class model
{
	public static function post()
	{
		$post                = [];
		$post['survey_id']     = \dash\request::get('id');
		$post['sort']        = \dash\request::post('sort');
		$post['sort_choise'] = true;
		$result              = \lib\app\question::sort_choise($post);

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}

	}
}
?>

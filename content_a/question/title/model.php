<?php
namespace content_a\question\title;


class model
{
	public static function post()
	{
		$post            = [];
		$post['title']   = \dash\request::post('title');
		$post['desc']    = \dash\request::post('desc');
		$post['survey_id'] = \dash\request::get('id');

		$file = \dash\app\file::upload_quick('media');

		if($file === false)
		{
			return false;
		}

		if($file)
		{
			$post['media']['file'] = $file;
		}

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

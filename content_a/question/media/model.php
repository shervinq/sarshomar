<?php
namespace content_a\question\media;


class model
{
	public static function post()
	{
		$post            = [];
		$post['title']   = \dash\request::post('title');
		$file = \dash\app\file::upload_quick('media');
		if($file === false)
		{
			return false;
		}

		if($file)
		{
			$post['media']['file'] = $file;

			$post['poll_id'] = \dash\request::get('id');

			$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

			if(\dash\engine\process::status())
			{
				\dash\redirect::pwd();
			}
		}
		else
		{
			\dash\notif::warn(T_("Please send a file to upload"));
		}

	}
}
?>

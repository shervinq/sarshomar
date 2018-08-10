<?php
namespace content_a\question\choice;


class model
{
	public static function post()
	{
		if(\dash\request::post('action') === 'remove')
		{
			$post['survey_id']       = \dash\request::get('id');
			$post['choice_key']    = \dash\request::post('key');
			$post['remove_choice'] = true;
			$result = \lib\app\question::edit($post, \dash\request::get('questionid'));
		}
		else
		{
			$post                = [];
			$post['survey_id']     = \dash\request::get('id');
			$post['choicetitle'] = \dash\request::post('choicetitle');
			$post['add_choice']  = true;

			$file = \dash\app\file::upload_quick('media');

			if($file === false)
			{
				return false;
			}

			if($file)
			{
				$post['choicefile'] = $file;
			}


			$result = \lib\app\question::edit($post, \dash\request::get('questionid'));
		}

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

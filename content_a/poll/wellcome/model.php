<?php
namespace content_a\poll\wellcome;


class model
{
	public static function post()
	{
		$post                  = [];
		$post['wellcometitle'] = \dash\request::post('wellcometitle');
		$post['wellcomedesc']  = \dash\request::post('wellcomedesc');

		$file = \dash\app\file::upload_quick('wellcomefile');

		if($file === false)
		{
			return false;
		}

		if($file)
		{
			$post['wellcomemedia']['file']  = $file;
		}
		else
		{
			$old = \content_a\poll\view::load();
			if(isset($old['wellcomemedia']['file']))
			{
				$post['wellcomemedia']['file']  = $old['wellcomemedia']['file'];
			}
		}

		$result = \lib\app\poll::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

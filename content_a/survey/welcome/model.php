<?php
namespace content_a\survey\welcome;


class model
{
	public static function post()
	{
		$post                  = [];
		$post['welcometitle'] = \dash\request::post('welcometitle');
		$post['welcomedesc']  = \dash\request::post('welcomedesc');

		$file = \dash\app\file::upload_quick('welcomefile');

		if($file === false)
		{
			return false;
		}

		if($file)
		{
			$post['welcomemedia']['file']  = $file;
		}
		else
		{
			$old = \content_a\survey\view::load_survey();
			if(isset($old['welcomemedia']['file']))
			{
				$post['welcomemedia']['file']  = $old['welcomemedia']['file'];
			}
		}

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

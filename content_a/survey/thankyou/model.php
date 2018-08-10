<?php
namespace content_a\survey\thankyou;


class model
{
	public static function post()
	{

		$post                  = [];
		$post['thankyoutitle'] = \dash\request::post('thankyoutitle');
		$post['thankyoudesc']  = \dash\request::post('thankyoudesc');

		$file = \dash\app\file::upload_quick('thankyoufile');

		if($file === false)
		{
			return false;
		}

		if($file)
		{
			$post['thankyoumedia']['file']  = $file;
		}
		else
		{
			$old = \content_a\survey\view::load_survey();
			if(isset($old['thankyoumedia']['file']))
			{
				$post['thankyoumedia']['file']  = $old['thankyoumedia']['file'];
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

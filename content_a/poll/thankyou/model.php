<?php
namespace content_a\poll\thankyou;


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
			$old = \content_a\poll\view::load_poll();
			if(isset($old['thankyoumedia']['file']))
			{
				$post['thankyoumedia']['file']  = $old['thankyoumedia']['file'];
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

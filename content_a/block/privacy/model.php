<?php
namespace content_a\block\privacy;


class model
{
	public static function post()
	{
		$post             = [];
		$post['privacy']  = \dash\request::post('privacy');
		$post['redirect'] = \dash\request::post('redirect') ? $_POST['redirect'] : null;
		$post['password'] = \dash\request::post('password');

		$result = \lib\app\block::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

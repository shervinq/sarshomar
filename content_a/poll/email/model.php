<?php
namespace content_a\poll\email;


class model
{
	public static function post()
	{

		$post               = [];
		$post['email']      = \dash\request::post('email');
		$post['emailtitle'] = \dash\request::post('emailtitle');
		$post['emailmsg']   = \dash\request::post('emailmsg');
		$post['emailto']    = \dash\request::post('emailto');

		$result = \lib\app\poll::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

<?php
namespace content_a\survey\email;


class model
{
	public static function post()
	{

		$post               = [];
		$post['email']      = \dash\request::post('email');
		$post['emailtitle'] = \dash\request::post('emailtitle');
		$post['emailmsg']   = \dash\request::post('emailmsg') ? $_POST['emailmsg'] : null;
		$post['emailto']    = \dash\request::post('emailto');

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

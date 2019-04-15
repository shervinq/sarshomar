<?php
namespace content_a\survey\security;


class model
{
	public static function post()
	{
		$post              = [];
		$post['fav']  = \dash\request::post('fav');

		$post['startdate'] = \dash\request::post('startdate');
		$post['enddate']   = \dash\request::post('enddate');
		$post['starttime'] = \dash\request::post('starttime');
		$post['endtime']   = \dash\request::post('endtime');
		$post['redirect'] = \dash\request::post('redirect') ? $_POST['redirect'] : null;
		// $post['password'] = \dash\request::post('password');

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

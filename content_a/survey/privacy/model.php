<?php
namespace content_a\survey\privacy;


class model
{
	public static function post()
	{
		$post                    = [];

		$post['redirect']        = \dash\request::post('redirect') ? $_POST['redirect'] : null;
		$post['forcelogin']      = \dash\request::post('forcelogin');
		$post['mobiles']         = \dash\request::post('mobiles');
		$post['mobilescheck']    = \dash\request::post('mobilescheck');
		$post['autoredirect']    = \dash\request::post('autoredirect');
		$post['redirecttime']    = \dash\request::post('redirecttime');
		$post['redirectcaption'] = \dash\request::post('redirectcaption');


		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

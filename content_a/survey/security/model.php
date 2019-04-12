<?php
namespace content_a\survey\security;


class model
{
	public static function post()
	{
		$post              = [];
		$post['template']  = \dash\request::post('template');

		$post['startdate'] = \dash\request::post('startdate');
		$post['enddate']   = \dash\request::post('enddate');
		$post['starttime'] = \dash\request::post('starttime');
		$post['endtime']   = \dash\request::post('endtime');

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

<?php
namespace content_a\survey\advance;


class model
{
	public static function post()
	{
		$post                       = [];
		$post['fav']                = \dash\request::post('fav');
		$post['startdate']          = \dash\request::post('startdate');
		$post['enddate']            = \dash\request::post('enddate');
		$post['starttime']          = \dash\request::post('starttime');
		$post['endtime']            = \dash\request::post('endtime');
		$post['schedule']           = \dash\request::post('schedule');
		$post['surveytime']         = \dash\request::post('surveytime');
		$post['questiontime']       = \dash\request::post('questiontime');
		$post['selectivecount']     = \dash\request::post('selectivecount');
		$post['randomquestion']     = \dash\request::post('randomquestion');
		$post['cannotreview']       = \dash\request::post('cannotreview');
		$post['cannotupdateanswer'] = \dash\request::post('cannotupdateanswer');



		// $post['referer']   = \dash\request::post('referer');

		// $post['redirect'] = \dash\request::post('redirect') ? $_POST['redirect'] : null;
		// $post['password'] = \dash\request::post('password');

		$result = \lib\app\survey::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

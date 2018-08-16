<?php
namespace content_a\question\home;


class model
{
	public static function post()
	{

		if(\dash\request::post('type') === 'remove' && \dash\request::post('id'))
		{
			\lib\app\question::delete(\dash\request::get('id'), \dash\request::post('id'));
			\dash\redirect::pwd();
		}
	}
}
?>

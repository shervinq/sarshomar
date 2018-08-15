<?php
namespace content_a\question\settings;


class model
{
	public static function post()
	{
		$post                = [];
		$post['require']     = \dash\request::post('require');
		$post['maxchar']     = \dash\request::post('maxchar');
		$post['min']         = \dash\request::post('min');
		$post['max']         = \dash\request::post('max');
		$post['choice_sort'] = \dash\request::post('choice_sort');
		$post['otherchoice'] = \dash\request::post('otherchoice');
		$post['placeholder'] = \dash\request::post('placeholder');
		$post['survey_id']   = \dash\request::get('id');

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

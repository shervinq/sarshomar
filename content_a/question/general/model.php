<?php
namespace content_a\question\general;


class model
{
	public static function post()
	{
		if(\dash\request::post('formType') === 'title')
		{
			return self::title();
		}
		elseif(\dash\request::post('formType') === 'question')
		{
			return self::choice();
		}
		else
		{
			return self::setting();
		}

	}

	public static function title()
	{
		$post            = [];
		$post['title']   = \dash\request::post('title');
		$post['desc']    = \dash\request::post('desc');
		$post['survey_id'] = \dash\request::get('id');

		$file = \dash\app\file::upload_quick('media');

		if($file === false)
		{
			return false;
		}

		if($file)
		{
			$post['media']['file'] = $file;
		}

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}

	public static function setting()
	{
		$post                 = [];
		$post['require']      = \dash\request::post('require');
		$post['maxchar']      = \dash\request::post('maxchar');
		$post['maxrate']      = \dash\request::post('maxrate');
		$post['choicehelp']   = \dash\request::post('choicehelp');
		$post['minchoice']    = \dash\request::post('minchoice');
		$post['maxchoice']    = \dash\request::post('maxchoice');
		$post['choiceinline'] = \dash\request::post('choiceinline');
		$post['ratetype']    = \dash\request::post('ratetype');
		$post['min']          = \dash\request::post('min');
		$post['max']          = \dash\request::post('max');
		$post['choice_sort']  = \dash\request::post('choice_sort');
		$post['otherchoice']  = \dash\request::post('otherchoice');
		$post['placeholder']  = \dash\request::post('placeholder');
		$post['survey_id']    = \dash\request::get('id');

		$result = \lib\app\question::edit($post, \dash\request::get('questionid'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}

	public static function choice()
	{
		if(\dash\request::post('action') === 'remove')
		{
			$post['survey_id']       = \dash\request::get('id');
			$post['choice_key']    = \dash\request::post('key');
			$post['remove_choice'] = true;
			$result = \lib\app\question::edit($post, \dash\request::get('questionid'));
		}
		else
		{
			$post                = [];
			$post['survey_id']     = \dash\request::get('id');
			$post['choicetitle'] = \dash\request::post('choicetitle');
			$post['add_choice']  = true;

			$file = \dash\app\file::upload_quick('media');

			if($file === false)
			{
				return false;
			}

			if($file)
			{
				$post['choicefile'] = $file;
			}


			$result = \lib\app\question::edit($post, \dash\request::get('questionid'));
		}

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

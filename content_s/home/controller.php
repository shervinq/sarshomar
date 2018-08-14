<?php
namespace content_s\home;

class controller
{
	public static function routing()
	{
		$module = \dash\url::module();
		$module = \dash\coding::decode($module);
		if(!$module)
		{
			\dash\redirect::to(\dash\url::base());
		}

		if(\dash\url::child())
		{
			if(\dash\url::child() === 'restart')
			{
				// restart to load new request
			}
			else
			{
				\dash\header::status(404);
			}
		}

		$load = \lib\app\survey::get(\dash\url::module());
		if(!$load || !isset($load['status']) || !isset($load['privacy']) || !isset($load['user_id']))
		{
			\dash\header::status(404, T_("Survay not found"));
		}

		if(isset($load['lang']))
		{
			if($load['lang'] !== \dash\language::current())
			{
				$new_url = \dash\url::base();
				$new_url .= '/'. $load['lang']. '/s/'. \dash\url::module();
				if(\dash\url::child())
				{
					$new_url .= '/'. \dash\url::module();
				}

				if(\dash\request::get())
				{
					$new_url .= '?'. \dash\url::query();
				}

				\dash\redirect::to($new_url);
			}
		}

		if(intval(\dash\coding::decode($load['user_id'])) === intval(\dash\user::id()))
		{
			\dash\data::mySurvey(true);
		}

		if(!\dash\permission::supervisor())
		{
			// check user id and privacy and password
			if($load['status'] !== 'publish')
			{
				if(!\dash\data::mySurvey())
				{
					\dash\header::status(403, T_("This survey is not publish"));
				}
			}
		}

		\dash\data::surveyRow($load);

		\dash\open::get();
		\dash\open::post();
	}

}
?>
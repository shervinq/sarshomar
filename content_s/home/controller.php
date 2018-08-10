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

		$load = \lib\app\survey::get(\dash\url::module());
		if(!$load || !isset($load['status']) || !isset($load['privacy']))
		{
			\dash\header::status(404, T_("Survay not found"));
		}

		if(!\dash\permission::supervisor())
		{
			// check user id and privacy and password
		}

		\dash\data::surveyRow($load);

		\dash\open::get();
		\dash\open::post();
	}

}
?>
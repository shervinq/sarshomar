<?php
namespace content_a;

class controller
{
	public static function routing()
	{
		if(!\dash\user::login())
		{
			\dash\redirect::to(\dash\url::base(). '/enter/signup?referer='. \dash\url::pwd());
			return;
		}
	}

}
?>
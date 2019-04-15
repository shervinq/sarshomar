<?php
namespace lib\app;


class tg
{
	public static function user_id()
	{
		if(\dash\user::id())
		{
			return \dash\coding::decode(\dash\user::id());
		}
		return false;
	}


	public static function list()
	{
		if(!self::user_id())
		{
			return false;
		}

		$args               = [];
		$args['pagenation'] = false;
		$args['status']     = 'publish';
		$args['user_id']    = self::user_id();
		$list               = \lib\app\survey::list(null, $args);

		// var_dump($list);
	}


	public static function welcome($_survey_id)
	{
		$get = \lib\app\survey::get($_survey_id);
		if(!self::check($get))
		{
			return false;
		}

		$welcometitle = 'DEFAULT WELCOME';
		if(isset($get['welcometitle']) && $get['welcometitle'])
		{
			$welcometitle = $get['welcometitle'];
		}

		$welcomedesc = 'DEFAULT WELCOME';
		if(isset($get['welcomedesc']) && $get['welcomedesc'])
		{
			$welcomedesc = $get['welcomedesc'];
		}

		$welcomemedia = null;
		if(isset($get['welcomemedia']['file']) && $get['welcomemedia']['file'])
		{
			$welcomemedia = $get['welcomemedia']['file'];
		}
		// var_dump($welcometitle, $welcomedesc, $welcomemedia);
	}


	public static function answer($_survey_id, $_question_id, $_answer)
	{
		if(!self::user_id())
		{
			return false;
		}

		$args           = [];
		$args['answer'] = $_answer;
		$result         = \lib\app\answer::add($_survey_id, $_question_id, $args);
		return $result;
	}


	public static function skip($_survey_id, $_question_id)
	{
		if(!self::user_id())
		{
			return false;
		}

		$args         = [];
		$args['skip'] = true;
		$result       = \lib\app\answer::add($_survey_id, $_question_id, $args);
		// var_dump($result);
	}


	public static function question($_question_id)
	{
		return \lib\app\question::get_by_answered($_survey_id, self::user_id());
	}


	public static function thankyou()
	{
		$get = \lib\app\survey::get($_survey_id);
		if(!self::check($get))
		{
			return false;
		}

		$thankyoutitle = 'DEFAULT WELCOME';
		if(isset($get['thankyoutitle']) && $get['thankyoutitle'])
		{
			$thankyoutitle = $get['thankyoutitle'];
		}

		$thankyoudesc = 'DEFAULT WELCOME';
		if(isset($get['thankyoudesc']) && $get['thankyoudesc'])
		{
			$thankyoudesc = $get['thankyoudesc'];
		}

		$thankyoumedia = null;
		if(isset($get['thankyoumedia']['file']) && $get['thankyoumedia']['file'])
		{
			$thankyoumedia = $get['thankyoumedia']['file'];
		}
		// var_dump($thankyoutitle, $thankyoudesc, $thankyoumedia);
	}


	public static function check($_survey_detail)
	{
		if(!$_survey_detail || !isset($_survey_detail['status']) || !isset($_survey_detail['privacy']) || !isset($_survey_detail['user_id']))
		{
			return false;
		}

		if($_survey_detail['status'] !== 'publish')
		{
			return false;
		}

		return true;
	}
}
?>
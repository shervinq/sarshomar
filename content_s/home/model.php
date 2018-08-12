<?php
namespace content_s\home;


class model
{
	public static function check_hiden_input()
	{
		$survay      = \dash\request::post('survay');
		$userprocode = \dash\request::post('userprocode');
		$passwd      = \dash\request::post('passwd');
		$id          = \dash\request::post('id');

		if($survay || $userprocode || $passwd || $id)
		{
			return false;
		}
		return true;
	}


	public static function check_xkey_xvalue()
	{
		$XKEY   = \dash\session::get('XKEY_'. \dash\url::module());
		$XVALUE = \dash\session::get('XVALUE_'. \dash\url::module());
		if(\dash\request::post($XKEY) === $XVALUE)
		{
			return true;
		}
		return false;
	}


	public static function post()
	{
		if(!self::check_hiden_input())
		{
			\dash\notif::error(T_("Dont!"));
			return false;
		}

		if(!self::check_xkey_xvalue())
		{
			\dash\notif::error(T_("Dont!"));
			return false;
		}

		if(!\dash\request::get('step'))
		{
			if(!\dash\user::id())
			{
				$user_id = \dash\db\users::signup();
				\dash\user::init($user_id);
				\dash\db\sessions::set($user_id);
				\dash\notif::direct();
			}

			$query = ['step' => 1];
			\dash\redirect::to(\dash\url::this().'?'. http_build_query($query));
			return;
		}

		if(!\dash\user::id())
		{
			\dash\notif::error(T_("Please login to continue"));
			return false;
		}

		$post           = [];
		$post['answer'] = \dash\request::post('answer');
		$post['skip']   = \dash\request::post('skip');

		$result         = \lib\app\answer::add(\dash\url::module(), \dash\request::get('step'), $post);

		if(!$result)
		{
			return false;
		}

		$step  = \dash\request::get('step');
		$step  = intval($step) + 1;
		$query = ['step' => $step];

		\dash\redirect::to(\dash\url::this().'?'. http_build_query($query));

	}
}
?>
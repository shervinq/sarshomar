<?php
namespace content_s\home;


class model
{
	public static function check_xkey_xvalue()
	{
		if(\dash\request::post("start") === "survey")
		{
			$XKEY   = \dash\session::get('XKEY_'. \dash\url::module());
			$XVALUE = \dash\session::get('XVALUE_'. \dash\url::module());
			if(\dash\request::post($XKEY) === $XVALUE)
			{
				return true;
			}
			return false;
		}
		return true;
	}


	public static function post()
	{

		if(!self::check_xkey_xvalue())
		{
			\dash\notif::error(T_("Dont!"));
			return false;
		}

		if(!\dash\request::get('step'))
		{
			if(!\dash\user::id())
			{
				$survay_setting = \dash\data::surveyRow();
				if(isset($survay_setting['setting']['forcelogin']) && $survay_setting['setting']['forcelogin'])
				{
					\dash\redirect::to(\dash\url::kingdom(). '/enter?referer='. \dash\url::pwd());
				}
				else
				{
					$user_id = \dash\db\users::signup();
					\dash\user::init($user_id);
					\dash\db\sessions::set($user_id);
					\dash\notif::direct();
				}
			}
			else
			{
				$survay_setting = \dash\data::surveyRow();
				if(isset($survay_setting['setting']['forcelogin']) && $survay_setting['setting']['forcelogin'])
				{
					if(!\dash\user::detail('verifymobile'))
					{
						$msg = T_("Plase verify your mobile to continue");
						$msg .= ' <a href="'. \dash\url::kingdom(). '/enter/verify?referer='.\dash\url::pwd(). '">'. T_("Click to verify"). '</a>';
						\dash\notif::error($msg);
						return false;
					}
				}
				else
				{
					// no problem to user all user from this survey
				}
			}

			$query = ['step' => 1];
			\dash\redirect::to(\dash\url::that().'?'. http_build_query($query));
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

		$result         = \lib\app\answer::add(\dash\url::module(), \dash\request::post('questionid'), $post);

		if(!$result)
		{
			return false;
		}

		$step = isset($result['step']) ? $result['step'] : null;
		// $step  = \dash\request::get('step');
		// $step  = intval($step) + 1;
		$query = ['step' => $step];

		\dash\redirect::to(\dash\url::that().'?'. http_build_query($query));

	}
}
?>
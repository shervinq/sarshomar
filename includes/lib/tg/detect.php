<?php
namespace lib\tg;
use \dash\social\telegram\tg as bot;


class detect
{
	public static function run($_cmd)
	{
		if(bot::isInline())
		{
			inline::search($_cmd);
		}
		else
		{
			survey::detector($_cmd);
			hafez::check($_cmd);
		}
	}


	public static function mainmenu($_onlyMenu = false)
	{
		// define
		$menu =
		[
			'keyboard' =>[],
			'resize_keyboard' => true,
		];

		$menu['keyboard'][] = [T_("Survey List")];
		// add about and contact link
		$menu['keyboard'][] = [T_("About"), T_("Contact")];

		// add sync
		if(\dash\user::detail('mobile'))
		{
			$menu['keyboard'][] = [T_("Website"). ' '. T_(\dash\option::config('site', 'title'))];
		}
		else
		{
			$menu['keyboard'][] = [T_("Sync with website")];
		}

		if($_onlyMenu)
		{
			return $menu;
		}

		$txt_text = T_("Main menu");

		$result =
		[
			'text'                => $txt_text,
			'reply_markup'        => $menu,
		];

		bot::sendMessage($result);
		bot::ok();
	}
}
?>
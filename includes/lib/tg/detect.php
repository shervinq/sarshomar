<?php
namespace lib\tg;


class detect
{
	public static function run($_cmd)
	{
		survey::detector($_cmd);
	}


	public static function mainmenu($_onlyMenu = false)
	{
		// define
		$menu =
		[
			'keyboard' =>
			[
				[T_("List")],
				[T_("About"), T_("Contact")],
			],
			'resize_keyboard' => true,
		];

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
<?php
namespace lib\tg;
use \dash\social\telegram\tg as bot;


class hafez
{
	public static function check($_cmd)
	{
		switch ($_cmd['command'])
		{
			case '/fal':
			case 'fal':
			case '/hafez':
			case 'hafez':
			case 'فال':
			case 'فالگیر':
			case 'فال بگیر':
			case 'فال حافظ':
			case 'حافظ':
			case 'تفعل':
			case 'خواجه':
			case 'بگو ای خواجه':
			case 'خواجه شیرازی':
			case 'ای حافظ شیرازی':
				self::niyat();
				return true;
				break;


			case 'cb_hafez_say_something':
				// if start with callback answer callback
				if(bot::isCallback())
				{
					self::fal();
				}
				break;


			case 'cb_hafez_read_it':
				// if start with callback answer callback
				if(bot::isCallback())
				{
					self::falReader();
				}
				break;

		}

		return false;
	}


	private static function niyat()
	{
		bot::ok();

		$text .= '<b>فال #حافظ</b>'. "\n";
		$text .= ''. "\n";
		$text .= ''. "\n";
		$text .= ''. "\n";

		$result =
		[
			'text' => $text,
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Lets go"),
							'callback_data' => 'hafez_say_something',
						],
					]
				]
			]
		];
		bot::sendMessage($result);
	}


	private static function fal()
	{
		bot::ok();
		bot::answerCallbackQuery(T_("Fal of Hafez"));

		// get random fal from hafez

		$text .= T_('Soon'). "\n";
		$text .= ''. "\n";
		$text .= ''. "\n";
		$text .= ''. "\n";

		$result =
		[
			'text' => $text,
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Lets go"),
							'callback_data' => 'hafez_read_it 12',
						],
					]
				]
			]
		];
		bot::sendMessage($result);
	}



	private static function falReader()
	{
		bot::ok();
		bot::answerCallbackQuery(T_("Fal of Hafez"). ' 🎻');

		// get random fal from hafez

		$text .= T_('Soon'). "\n";
		$text .= ''. "\n";
		$text .= ''. "\n";
		$text .= ''. "\n";

		$result =
		[
			'text' => $text,
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Open :val website", ['val' => T_(\dash\option::config('site', 'title'))]),
							'url'  => bot::website(),
						],
					]
				]
			]
		];
		bot::sendMessage($result);
	}

}
?>
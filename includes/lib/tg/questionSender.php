<?php
namespace lib\tg;


class questionSender
{
	public static function send($_questionData)
	{
		var_dump($_questionData);
		exit();
		$txt_text = T_("Hello This is question one");
		// empty keyboard
		$result =
		[
			'text'         => $txt_text,
			'reply_markup' =>
			[
				'keyboard' => [[T_('Cancel')]],
				'resize_keyboard' => true,
				'one_time_keyboard' => true
			],
		];
		bot::sendMessage($result);
	}

}
?>
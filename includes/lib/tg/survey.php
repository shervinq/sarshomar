<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;
use \dash\social\telegram\step;

class survey
{
	public static function show($_id)
	{
		bot::ok();

		$surveyNo = $_id;
		$survey = \lib\app\tg\survey::get($surveyNo);

		if($survey)
		{
			$result =
			[
				'text'         => $survey,
				'reply_markup' =>
				[
					'inline_keyboard' =>
					[
						[
							[
								'text' => T_("Answer via site"),
								'url'  => \dash\url::base(). '/s/'. $surveyNo,
							],
						],
						[
							[
								'text'          => 	T_("Start"),
								'callback_data' => 'survey '. $surveyNo. ' start',
							],
						],
					]
				]
			];

			// if start with callback answer callback
			if(bot::isCallback())
			{
				$callbackResult =
				[
					'text' => T_("Survey"). ' '. $surveyNo,
				];
				bot::answerCallbackQuery($callbackResult);
			}

			bot::sendMessage($result);
		}
		else
		{
			if(bot::isCallback())
			{
				$callbackResult =
				[
					'text' => T_("We can't find detail of this survey!"),
					'show_alert' => true,
				];
				bot::answerCallbackQuery($callbackResult);
			}
			// else
			// {
			// 	// $result =
			// 	// [
			// 	// 	'text' => T_("Survey id is not found")." 🙁",
			// 	// ];
			// 	// bot::sendMessage($result);
			// }
		}
	}


	public static function requireCode()
	{
		bot::ok();
		$msg = T_("We need survey code!")." 🙁";

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => $msg,
			];
			bot::answerCallbackQuery($callbackResult);
		}

		$result =
		[
			'text' => $msg,
		];
		bot::sendMessage($result);
	}


	public static function empty()
	{
		bot::ok();
		$msg = T_("You must have survey id to use in telegram.")." 🙁";

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => $msg,
			];
			bot::answerCallbackQuery($callbackResult);
		}

		$result =
		[
			'text' => $msg,
		];
		bot::sendMessage($result);
	}
}
?>
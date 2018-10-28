<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;
use \dash\social\telegram\step;

class survey
{


	public static function detector($_cmd)
	{
		$myCommand = $_cmd['commandRaw'];
		if(bot::isCallback())
		{
			$myCommand = substr($myCommand, 3);
		}
		elseif(bot::isInline())
		{
			$myCommand = substr($myCommand, 3);
		}
		// remove survey from start of command
		if(substr($myCommand, 0, 7) !== 'survey_')
		{
			return false;
		}
		// detect survey No
		$surveyNo = substr($myCommand, 7);

		if(!$surveyNo)
		{
			survey::empty();
			return false;
		}
		// if code is not valid show related message
		if(!\dash\coding::is($surveyNo))
		{
			survey::requireCode();
			return false;
		}
		// detect opt
		$myOpt = null;
		if(isset($_cmd['optionalRaw']) && $_cmd['optionalRaw'])
		{
			$myOpt = $_cmd['optionalRaw'];
		}
		// detect arg
		$myArg = null;
		if(isset($_cmd['argumentRaw']) && $_cmd['argumentRaw'])
		{
			$myArg = $_cmd['argumentRaw'];
		}


		if($myOpt === null)
		{
			survey::show($surveyNo);
			return true;
		}
		if($myOpt === 'start' && bot::isCallback())
		{
			step_survey::start($surveyNo);
			return true;
		}
		// if we are in step skip check and continue step
	}



	public static function show($_id)
	{
		bot::ok();

		$surveyNo  = $_id;
		$surveyTxt = \lib\app\tg\survey::get($surveyNo);
		$surveyTxt .= "\n\n". T_("You can cancel answer operation anytime by send command /cancel");

		if($surveyTxt)
		{
			$result =
			[
				'text'         => $surveyTxt,
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
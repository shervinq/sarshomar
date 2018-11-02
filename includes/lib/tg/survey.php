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
		// remove command from start
		if(substr($myCommand, 0, 1) == '/')
		{
			$myCommand = substr($myCommand, 1);
		}
		// remove survey from start of command
		// and detect survey No
		$surveyNo = null;
		if(substr($myCommand, 0, 7) === 'survey_')
		{
			$surveyNo = substr($myCommand, 7);
		}
		elseif(substr($myCommand, 0, 2) === 's_')
		{
			$surveyNo = substr($myCommand, 2);
		}
		elseif(substr($myCommand, 0, 1) === '$' && strlen($myCommand) > 1)
		{
			$surveyNo = substr($myCommand, 1);
		}
		elseif($myCommand === 'survey' || $myCommand === '$' || $myCommand === 'list' || $myCommand === T_('list') || $myCommand === T_('survey'))
		{
			// show list of survey
			survey::list();
			return true;
		}
		elseif($myCommand === 'how' || $myCommand === 'howto')
		{
			// show list of survey
			survey::list(true);
			return true;
		}
		else
		{
			return false;
		}
		// remove botname from surveyNo if exist
		$surveyNo = strtok($surveyNo, '@');
		// check if survey id is not exist show list
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
			survey::welcome($surveyNo);
			return true;
		}
		elseif($myOpt === 'start' && bot::isCallback())
		{
			step_answering::start($surveyNo);
			return true;
		}
		elseif($myOpt === 'end')
		{
			survey::thankyou($surveyNo);
			return true;
		}
		else
		{
			bot::ok();

			// remove keyboard of old messages
			$newMsg =
			[
				'reply_markup' =>
				[
					'inline_keyboard' =>
					[
						[
							[
								'text' => T_("Sarshomar website"),
								'url'  => \dash\url::kingdom(),
							],
						]
					]
				]
			];
			bot::editMessageReplyMarkup($newMsg);
			// show funny message
			bot::answerCallbackQuery(T_('Please do not play with them!'). ' 🤖');
		}
		// if we are in step skip check and continue step
	}



	public static function welcome($_surveyId)
	{
		bot::ok();

		$surveyTxt = \lib\app\tg\survey::get($_surveyId);

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
								'url'  => \dash\url::base(). '/s/'. $_surveyId,
							],
						],
						[
							[
								'text'          => 	T_("Start"),
								'callback_data' => 'survey_'. $_surveyId. ' start',
							],
						],
					]
				]
			];

			if(!bot::isPrivate())
			{
				$result['reply_markup']['inline_keyboard'][1] =
				[
					[
						'text' => T_("Answer via bot"),
						'url'  => 'https://t.me/'. bot::$name. '?start=survey_'. $_surveyId .' start',
					],
				];
			}

			// if start with callback answer callback
			if(bot::isCallback())
			{
				$callbackResult =
				[
					'text' => T_("Survey"). ' '. $_surveyId,
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
		}
	}


	public static function thankyou($_surveyId)
	{
		bot::ok();

		$surveyTxt = \lib\app\tg\survey::get($_surveyId, 'thankyou');

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
								'text' => T_("Sarshomar website"),
								'url'  => \dash\url::kingdom(),
							],
						]
					]
				]
			];

			// if start with callback answer callback
			if(bot::isCallback())
			{
				$callbackResult =
				[
					'text' => T_("Survey"). ' '. $_surveyId,
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


	public static function list($_detail = null)
	{
		bot::ok();

		// if start with callback answer callback
		if(bot::isCallback())
		{
			bot::answerCallbackQuery(T_("List of your survey in Sarshomar"));
		}
		if($_detail === null)
		{
			$surveyList = \lib\app\tg\survey::list();
			if($surveyList)
			{
				bot::sendMessage($surveyList);
				return true;
			}
		}

		// show message to go to website
		$msg = T_('You have no survey yet!') ."\n\n";
		$msg .= "<b>". T_('Sarshomar is changed'). "</b>\n";
		$msg .= T_('To add new survey you must go to :val and try to add new survey and you can use many of or features.', ['val' => '<a href="sarshomar.com">'. T_("Sarshomar website").'</a>']);
		$msg .= "\n\n";
		$msg .= T_('If you are complete /register in telegram bot and sync your account with website, after create new survey we are send survey link here in telegram and you can access it via /list command anytime.');

		$result =
		[
			'text' => $msg,
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Add new survey"),
							'url'  => \dash\url::kingdom().'/a',
						],
					],
				]
			]
		];

		// add sync
		if(!\dash\user::detail('mobile'))
		{
			$result['reply_markup']['inline_keyboard'][][] =
			[
				'text'          => T_("Sync with website"),
				'callback_data' => 'sync',
			];
		}
		var_dump($result);

		bot::sendMessage($result);
	}



	public static function goToPrivate($_id = null)
	{
		bot::ok();

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => T_("Please come into private"). ' 😅',
			];
			bot::answerCallbackQuery($callbackResult);
		}

		$result =
		[
			'text' => T_("Please start answer to survey in private message not in public!")." 😗",
			'reply_markup' =>
			[
				'inline_keyboard' =>
				[
					[
						[
							'text' => T_("Answer via bot"),
							'url'  => 'https://t.me/'. bot::$name. '?start=/survey_$_id',
						],
					],
				]
			]
		];
		bot::sendMessage($result);
	}
}
?>
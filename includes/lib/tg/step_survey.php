<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;
use \dash\social\telegram\step;

class step_survey
{
	public static function start($_id)
	{
		// its okay on start
		bot::ok();

		step::set('surveyNo', $_id);
		step::start('surveyAnswer');

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => T_("Answer to survey "). $_id,
			];
			bot::answerCallbackQuery($callbackResult);
		}

		return self::step1();
	}


	// show question
	public static function step1()
	{
		step::plus();
		$txt_text = T_("Hello This is question one");
		$surveyNo = step::get('surveyNo');
		step::set('questionID', 12);

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


	// get answer
	public static function step2($_answer)
	{
		if(step::checkFalseTry())
		{
			return false;
		}

		// save answer
		$surveyNo   = step::get('surveyNo');
		$questionId = step::get('questionID');
		$saveResult = \lib\app\tg\survey::answer($surveyNo, $questionId, $_answer)

		$nextIsExist = null;
		// check next question if exist show it
		// else show thankyou msg
		if($nextIsExist)
		{
			// go to next message
			step::goingto(1);
			return self::step1();
		}
		else
		{
			// show thankyou msg
			survey::thankyou($surveyNo);
			step::stop();
		}
	}
}
?>
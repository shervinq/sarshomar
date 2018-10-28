<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;
use \dash\social\telegram\step;

class step_answering
{
	public static function start($_id)
	{
		// its okay on start
		bot::ok();

		step::set('surveyNo', $_id);
		step::start('answering');

		// if start with callback answer callback
		if(bot::isCallback())
		{
			$callbackResult =
			[
				'text' => T_("Answer to survey"). ' '. $_id,
			];
			bot::answerCallbackQuery($callbackResult);
		}

		return self::step1();
	}


	// show question
	public static function step1($_txt = null, $_step = null)
	{
		// init first step
		if($_step === null)
		{
			$_step = 1;
		}
		$surveyNo = step::get('surveyNo');
		step::set('surveyStep', $_step);
		// get question of this step
		$myQuestion = \lib\app\tg\survey::get($surveyNo, $_step);
		// send question
		questionSender::analyse($myQuestion);

		// go to next step to get answer
		step::plus();
	}


	// get answer
	public static function step2($_answer)
	{
		if(step::checkFalseTry())
		{
			return false;
		}

		bot::sendMessage('test, answer to question....');



		// save answer
		$surveyNo   = step::get('surveyNo');
		$surveyStep = step::get('surveyStep');
		$saveResult = \lib\app\tg\survey::answer($surveyNo, $surveyStep, $_answer);

		$nextIsExist = null;
		// check next question if exist show it
		// else show thankyou msg
		if($nextIsExist)
		{
			// increase step of survey
			$surveyStep++;
			step::set('surveyStep', $surveyStep);
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
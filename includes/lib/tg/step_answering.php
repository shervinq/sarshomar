<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;
use \dash\social\telegram\hook;
use \dash\social\telegram\step;

class step_answering
{
	public static function start($_id)
	{
		// dont run on public chats!
		if(!bot::isPrivate())
		{
			survey::goToPrivate($_id);
			return false;
		}

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

			$initMsg =
			[
				'text' => T_("You can cancel answer operation anytime by send command /cancel or skip current question by send /skip"),
				'reply_markup' => ['remove_keyboard' => true]
			];
			bot::sendMessage($initMsg);
		}
		$surveyNo = step::get('surveyNo');
		step::set('surveyStep', $_step);
		// get question of this step
		$myQuestion    = \lib\app\tg\survey::get($surveyNo, $_step);
		$userAnswerArr = \dash\data::myAnswerTitle();
		if(isset($myQuestion['id']))
		{
			step::set('questionId', $myQuestion['id']);
		}
		else
		{
			// show thankyou msg
			survey::thankyou($surveyNo);
			step::stop();
			return true;
		}
		// send question
		questionSender::analyse($myQuestion, $userAnswerArr);
		// set type of question
		if(isset($_questionData['type']))
		{
			step::set('questionType', $_questionData['type']);
		}

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
		// define variables
		$surveyNo     = step::get('surveyNo');
		$surveyStep   = step::get('surveyStep');
		$questionId   = step::get('questionId');
		$questionType = step::get('questionType');

		if(bot::isCallback())
		{
			if(substr($_answer, 0, 3) === 'cb_')
			$_answer = substr($_answer, 3);

			$fakeAnswer = true;
			$cmd = hook::cmd();

			if($cmd['commandRaw'] === 'cb_survey_'. $surveyNo)
			{
				if($cmd['optionalRaw'] === $questionId)
				{
					$fakeAnswer = false;
					$_answer    = $cmd['argumentRaw'];
				}
			}

			if($fakeAnswer)
			{
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
				bot::answerCallbackQuery('❌ '. T_('How are you!'));
				// show false try message
				step::checkFalseTry(true);
				return false;
			}
			else
			{
				// reset false try if user send correct answer
				step::set('falseTry', 0);
			}

			// answer callback result
			bot::answerCallbackQuery('#'. $surveyStep. ' '. T_("Answer received"));

			if($questionType === 'multiple_choice')
			{
				// dont send message
			}
			else
			{
				// send message of recieve on callback
				$receiveMsg =
				[
					'text' => T_("Your answer"). "\n<b>". $_answer. '</b>',
					'reply_markup' => ['remove_keyboard' => true],
					'disable_notification' => true,
				];
			}
			bot::sendMessage($receiveMsg);
		}
		if($_answer === '/skip')
		{
			$saveResult = \lib\app\tg\survey::skip($surveyNo, $questionId);
		}
		else
		{
			if($questionType === 'multiple_choice')
			{
				$multipleAnswers = step::get('multipleAnswers');
				if(!is_array($multipleAnswers))
				{
					$multipleAnswers = [];
				}

				if($_answer === '/save')
				{
					// save answer
					$saveResult = \lib\app\tg\survey::answer($surveyNo, $questionId, $multipleAnswers);
				}
				else
				{
					if(in_array($_answer, $multipleAnswers))
					{
						// unset
						$myKey = array_search($_answer, $multipleAnswers);
						if($myKey !== false)
						{
							unset($multipleAnswers[$myKey]);
						}
					}
					else
					{
						array_push($multipleAnswers, $_answer);

					}
					// set in variable
					step::set('multipleAnswers', $multipleAnswers);
				}

			}
			else
			{
				// save answer
				$saveResult = \lib\app\tg\survey::answer($surveyNo, $questionId, $_answer);
			}
		}


		if($saveResult)
		{
			// increase step of survey
			$surveyStep++;
			// go to next message
			step::goingto(1);

			return self::step1(null, $surveyStep);
		}
		else
		{
			// notif created on app based on question type
		}
	}
}
?>
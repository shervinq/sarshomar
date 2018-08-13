<?php
namespace content_s\home;


class view
{
	public static function config()
	{
		\dash\data::page_title(T_("Survey"));
		$page_title = \dash\data::surveyRow_title();
		if($page_title)
		{
			\dash\data::page_title($page_title);
		}

		\dash\data::page_desc(T_("Description of survey"));
		// $page_desc = \dash\data::surveyRow_desc();
		// if($page_desc)
		// {
		// 	\dash\data::page_desc($page_desc);
		// }

		$survey = \dash\data::surveyRow();

		$display_step = 'start';

		if(isset($survey['wellcometitle']) || isset($survey['wellcomedesc']) || isset($survey['wellcomemedia']['file']))
		{
			$step = 'wellcome';
		}

		$step = \dash\request::get('step');

		if($step && is_numeric($step))
		{
			$step = intval($step);

			// if not login go to first page to signup firset
			if(!\dash\user::id())
			{
				\dash\redirect::to(\dash\url::this());
				return;
			}


			$question = \lib\app\question::get_by_step(\dash\url::module(), $step);
			if(!$question || !isset($question['type']))
			{
				\dash\header::status(404, T_("Invalid question id"));
			}

			$answer = \lib\db\answers::get(['survey_id' => \dash\coding::decode(\dash\url::module()), 'user_id' => \dash\user::id(), 'limit' => 1]);

			$must_step = 1;

			if(isset($answer['step']) && $answer['step'])
			{
				$must_step = intval($answer['step']) + 1;
			}

			if($step === $must_step || $step < $must_step)
			{
				// no problem
			}
			else
			{
				\dash\redirect::to(\dash\url::this(). '?step='. $must_step);
			}

			$time_key = 'dateview_'. (string) \dash\coding::decode(\dash\data::surveyRow_id()). '_'. (string) $step;
			\dash\session::set($time_key, date("Y-m-d H:i:s"));

			\dash\data::question($question);
			$display_step = $question['type'];

			$myAnswer = \lib\app\answer::my_answer(\dash\url::module(), $question['id']);
			\dash\data::myAnswer($myAnswer);
		}
		else
		{
			\dash\data::nextQuestion(1);
		}

		self::make_xkey_xvalue();

		\dash\data::displayStep($display_step);
	}


	public static function make_xkey_xvalue()
	{
		$XKEY = md5(rand());
		\dash\session::set('XKEY_'. \dash\url::module(), $XKEY);
		\dash\data::XKEY($XKEY);

		$XVALUE = md5(rand());
		\dash\session::set('XVALUE_'. \dash\url::module(), $XVALUE);
		\dash\data::XVALUE($XVALUE);
	}
}
?>
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
		$page_desc = \dash\data::surveyRow_desc();
		if($page_desc)
		{
			\dash\data::page_desc($page_desc);
		}

		$survey = \dash\data::surveyRow();

		$step_display = 'start';

		if(isset($survey['welcometitle']) || isset($survey['welcomedesc']) || isset($survey['welcomemedia']['file']))
		{
			$step_display = 'welcome';
		}


		$step      = \dash\request::get('step');
		$must_step = null;
		$end_step  = null;


		if($step && is_numeric($step))
		{
			$step = intval($step);

			// if not login go to first page to signup firset
			if(!\dash\user::id())
			{
				\dash\redirect::to(\dash\url::this());
				return;
			}

			$end_step  = \dash\data::surveyRow_countblock();

			$question = \lib\app\question::get_by_step(\dash\url::module(), $step);

			if(!$question || !isset($question['type']))
			{
				if($step >= $end_step + 1)
				{
					$step_display = 'thankyou';
				}
				else
				{
					\dash\header::status(404, T_("Invalid question id"));
				}
			}

			$answer = \lib\db\answers::get(['survey_id' => \dash\coding::decode(\dash\url::module()), 'user_id' => \dash\user::id(), 'limit' => 1]);

			\dash\data::answerRow($answer);

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

			\dash\data::question($question);

			if($step_display !== 'thankyou')
			{
				$step_display = $question['type'];
			}

			if(isset($question['id']))
			{

				$time_key = 'dateview_'. (string) \dash\coding::decode(\dash\data::surveyRow_id()). '_'. (string) $step;
				\dash\session::set($time_key, date("Y-m-d H:i:s"));

				$myAnswer = \lib\app\answer::my_answer(\dash\url::module(), $question['id']);
				\dash\data::myAnswer($myAnswer);
			}

			if($step_display === 'thankyou')
			{
				if(isset($survey['thankyoutitle']) || isset($survey['thankyoudesc']) || isset($survey['thankyoumedia']['file']))
				{
					$step_display = 'thankyou';
				}
				else
				{
					$step_display = 'thankyoudefault';
				}
			}

		}
		else
		{
			\dash\data::nextQuestion(1);
		}

		self::make_xkey_xvalue();

		\dash\data::step_display($step_display);
		\dash\data::step_end($end_step);
		\dash\data::step_must($must_step);

		self::askDetail();
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


	public static function askDetail()
	{
		// $answer = \dash\data::answerRow();
		if(\dash\data::answerRow_startdate())
		{
			$start_time = date("Y-m-d H:i:s", strtotime(\dash\data::answerRow_startdate()));
		}
		else
		{
			$start_time = date("Y-m-d H:i:s");
		}

		$time_left = time() - strtotime($start_time);
		$min = intval($time_left / 60);
		$sec = intval($time_left % 60);


		$spend_time = "$min:$sec";
		$remain_time = null;
		$step = \dash\request::get('step');
		$count_block = \dash\data::surveyRow_countblock();
		$count_block = $count_block + 1; // welcome and thankyou step
		if(!$count_block)
		{
			$count_block = 1;
		}
		$completed = round((intval($step) * 100) / $count_block);

		$askDetail =
		[
			'start_time'  => date("H:i:s", strtotime($start_time)),
			'spend_time'  => $spend_time,
			'remain_time' => $remain_time,
			'end_time'    => null,
			'completed'   => $completed,
			'step'        => $step > \dash\data::surveyRow_countblock() ? \dash\data::surveyRow_countblock() : $step,
			'countblock'  => \dash\data::surveyRow_countblock(),
		];

		\dash\data::askDetail($askDetail);

	}
}
?>
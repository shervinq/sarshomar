<?php
namespace lib\app\tg;


class survey
{

	public static function get($_id, $_step = null)
	{
		$get = \lib\app\survey::fire($_id);
		if($get)
		{
			if(!$_step)
			{
				return self::wellcome_msg();
			}
			else
			{
				$load_step = \content_s\home\view::load($_id, $_step);
				if($load_step && is_array($load_step) && isset($load_step['must_step']) && $load_step['must_step'])
				{
					$load_step = \content_s\home\view::load($_id, $load_step['must_step']);
					return self::load_question();
				}
				elseif($load_step === true)
				{
					return self::load_question();
				}
				elseif(!$load_step)
				{
					return T_("Invalid step");
				}
				else
				{
					return 'unknown error';
				}
			}
		}
		else
		{
			\dash\notif::error(T_('Survey not found'));
			return false;
		}
	}

	public static function skip($_id, $_question_id)
	{
		return self::answer($_id, $_question_id, null, true);
	}

	public static function answer($_id, $_question_id, $_answer, $_skip = false)
	{
		$answer           = [];
		$answer['answer'] = $_answer;
		$answer['skip']   = $_skip;
		$result           = \lib\app\answer::add($_id, $_question_id, $answer);

		if(!$result)
		{
			return false;
		}
		// @check
		$step = 0;
		// $step  = \dash\request::get('step');
		$step  = intval($step) + 1;
		// new step
		return null;
	}


	public static function load_question()
	{
		$question = \dash\data::question();
		if(!isset($question['type']))
		{
			return false;
		}

		$title = self::title_detect();

		switch ($question['type'])
		{
			case 'multiple_choice':
				self::multiple_choice();
				break;

			case 'short_answer':
			case 'descriptive_answer':
			case 'numeric':
			case 'single_choice':
			case 'dropdown':
			case 'date':
			case 'time':
			case 'mobile':
			case 'email':
			case 'website':
			case 'rating':
			case 'rangeslider':

				break;

			default:
				// not support this type
				return false;
				break;
		}
	}


	private static function title_detect()
	{
		$question = \dash\data::question();
		if(!isset($question['title']))
		{
			return null;
		}
		return $question['title'];
	}


	private static function multiple_choice()
	{
		$question = \dash\data::question();
		$msg = '';
		if(isset($question['choice']) && is_array($question['choice']))
		{
			foreach ($question['choice'] as $key => $choice)
			{
				if(isset($choice['title']))
				{
					$msg .= $key . ': '. $choice['title']. "\n";

				}
			}

		}
		return $msg;
	}


	private static function wellcome_msg()
	{
		$survey = \dash\data::surveyRow();
		$msg = '';
		if(isset($survey['welcometitle']))
		{
			$msg .= "🔹 <b>". $survey['welcometitle']. "</b>\n\n";
		}

		if(isset($survey['welcomedesc']))
		{
			$temp = $survey['welcomedesc'];
			$temp = str_replace('&nbsp;', ' ', $temp);
			$temp = strip_tags($temp, '<br><b>');
			$msg .= $temp. "\n";
		}

		if(isset($survey['welcomemedia']['file']))
		{
			$msg .= "\n". "<a href='". $survey['welcomemedia']['file']. "'>". T_("Image"). "</a>";
		}

		if(!trim($msg))
		{
			$msg = T_("Let's go to start answer survey");
		}

		return $msg;
	}
}
?>
<?php
namespace lib\app\tg;


class survey
{
	public static function list()
	{
		$result            = '';
		$arg               = [];
		$arg['user_id']    = \dash\user::id();
		$arg['pagenation'] = false;
		$dataTable         = \lib\app\survey::list(null, $arg);

		foreach ($dataTable as $key => $value)
		{
			$thisSurvey = '';
			$thisSurvey .= '🔸 <b>'. $value['title'] . "</b>\n";
			$thisSurvey .= '/survey_'. $value['id'] . "\n—————\n";

			$result .= $thisSurvey;
		}

		return $result;
	}


	public static function get($_id, $_step = null)
	{
		$get = \lib\app\survey::fire($_id);
		if($get)
		{
			if(!$_step)
			{
				return self::wellcome_msg();
			}
			elseif($_step === 'thankyou')
			{
				return self::thankyou_msg();
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
		return true;
	}


	public static function load_question()
	{
		$question = \dash\data::question();
		if(!isset($question['type']))
		{
			return false;
		}
		return $question;
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
			$temp = str_replace('</p>', "</p>\n", $temp);
			$temp = strip_tags($temp, '<br><b>');
			$msg .= $temp;
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


	private static function thankyou_msg()
	{
		$survey = \dash\data::surveyRow();
		$msg = '';
		if(isset($survey['thankyoutitle']))
		{
			$msg .= "🔹 <b>". $survey['thankyoutitle']. "</b>\n\n";
		}

		if(isset($survey['thankyoudesc']))
		{
			$temp = $survey['thankyoudesc'];
			$temp = str_replace('&nbsp;', ' ', $temp);
			$temp = str_replace('</p>', "</p>\n", $temp);
			$temp = strip_tags($temp, '<br><b>');
			$msg .= $temp;
		}

		if(isset($survey['thankyoumedia']['file']))
		{
			$msg .= "\n". "<a href='". $survey['thankyoumedia']['file']. "'>". T_("Image"). "</a>";
		}

		if(!trim($msg))
		{
			$msg = T_("Thank you to answer our question");
		}

		return $msg;
	}
}
?>
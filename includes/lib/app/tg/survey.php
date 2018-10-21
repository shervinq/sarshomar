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
			return false;
		}
	}


	public static function answer($_id, $_step, $_answer)
	{

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
		return null;
	}


	private static function wellcome_msg()
	{
		$survey = \dash\data::surveyRow();
		$msg = '';
		if(isset($survey['welcometitle']))
		{
			$msg .= $survey['welcometitle']. "\n";
		}

		if(isset($survey['welcomedesc']))
		{
			$temp = $survey['welcomedesc'];
			$temp = strip_tags($temp);
			$temp = str_replace('&nbsp;', ' ', $temp);
			$msg .= $temp. "\n";
		}

		if(!trim($msg))
		{
			$msg = T_("Wellcome to sarshomar");
		}

		return $msg;
	}
}
?>
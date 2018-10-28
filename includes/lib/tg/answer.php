<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;


class answer
{
	public static function run($_cmd)
	{
		self::suveryDetector($_cmd);
	}


	private static function suveryDetector($_cmd)
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
		if(bot::isCallback() && $myOpt === 'start')
		{
			step_survey::start($surveyNo);
			return true;
		}
		// if we are in step skip check and continue step
	}
}
?>
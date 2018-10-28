<?php
namespace lib\tg;


class answer
{
	public static function run($_cmd)
	{
		switch ($_cmd['command'])
		{
			case '/survey':
			case '/poll':
			case '/s':
			case T_('survey'):
			case T_('poll'):
				if(isset($_cmd['optional']))
				{
					$surveyNo = \dash\utility\convert::to_en_number($_cmd['optional']);
					if(\dash\coding::is($surveyNo))
					{
						survey::show($surveyNo);
					}
					else
					{
						survey::requireCode();
					}
				}
				else
				{
					survey::empty();
				}
				break;
		}

	}
}
?>
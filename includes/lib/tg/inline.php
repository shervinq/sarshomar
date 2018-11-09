<?php
namespace lib\tg;
use \dash\social\telegram\tg as bot;


class inline
{
	public static function search($_cmd)
	{
		$surveyNo = null;
		if(isset($_cmd['commandRaw']) && substr($_cmd['commandRaw'], 0, 3) === 'iq_')
		{
			$surveyNo = substr($_cmd['commandRaw'], 3);
		}
		else
		{
			return false;
		}

		if(!\dash\coding::is($surveyNo))
		{
			return false;
		}

		$welcomeDetail = \lib\app\survey::get($surveyNo);
		$welcomeOkay   = \lib\app\survey::check($welcomeDetail);

		if($welcomeOkay)
		{
			bot::ok();
			self::showSurveyInline($surveyNo, $welcomeDetail);
		}
	}


	public static function showSurveyInline($_id, $_welcome)
	{
		$surveyTitle = 'Survey';
		if(isset($_welcome['title']) && $_welcome['title'])
		{
			$surveyTitle = $_welcome['title'];
		}

		$surveyDesc = 'Sarshomar';
		if(isset($_welcome['desc']) && $_welcome['desc'])
		{
			$surveyDesc = $_welcome['desc'];
			$surveyDesc = str_replace('&nbsp;', ' ', $surveyDesc);
			$surveyDesc = str_replace('</p>', "</p>\n", $surveyDesc);
			$surveyDesc = strip_tags($surveyDesc);
		}

		$welcomeMedia = \dash\url::site().'/static/images/logo.png';
		// if(isset($_welcome['welcomemedia']['file']) && $_welcome['welcomemedia']['file'])
		// {
		// 	$welcomeMedia = $_welcome['welcomemedia']['file'];
		// }
		// get txt of survey welcome msg
		$surveyTxt = \lib\app\tg\survey::get($_id);

		$resultInline =
		[
			'results' =>
			[
				[
					'type'                  => 'article',
					'id'                    => 1001,
					'title'                 => $surveyTitle,
					'description'           => $surveyDesc,
					'thumb_url'             => $welcomeMedia,
					'cache_time'            => 60,
					'input_message_content' =>
					[
						'message_text' => $surveyTxt,
						'parse_mode'   => 'html'
					],

					'reply_markup' =>
					[
						'inline_keyboard' =>
						[
							[
								[
									'text' => T_("Answer via site"),
									'url'  => \dash\url::base(). '/s/'. $_id,
								],
							],
							[
								[
									'text' => 	T_("Answer via bot"),
									'url'  => bot::deepLink('survey_'. $_id)
								],
							],
						]
					],
				]
			]
		];

		bot::answerInlineQuery($resultInline);

	}
}
?>
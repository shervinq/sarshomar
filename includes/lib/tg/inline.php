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
			bot::sendMessage($surveyNo);
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
		$welcomeTitle = 'Survey';
		if(isset($_welcome['welcometitle']) && $_welcome['welcometitle'])
		{
			$welcomeTitle = $_welcome['welcometitle'];
		}

		$welcomeDesc = 'Sarshomar';
		if(isset($_welcome['welcomedesc']) && $_welcome['welcomedesc'])
		{
			$welcomeDesc = $_welcome['welcomedesc'];
		}

		$welcomeMedia = \dash\url::site().'/static/images/logo.png';
		if(isset($_welcome['welcomemedia']['file']) && $_welcome['welcomemedia']['file'])
		{
			$welcomeMedia = $_welcome['welcomemedia']['file'];
		}
		// get txt of survey welcome msg
		$surveyTxt = \lib\app\tg\survey::get($_surveyId);

		$resultInline =
		[
			'results' =>
			[
				[
					'type'                  => 'article',
					'id'                    => 1001,
					'title'                 => $welcomeTitle,
					'description'           => $welcomeDesc,
					'thumb_url'             => $welcomeMedia,
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
									'text'          => 	T_("Answer via bot"),
									'callback_data' => 'survey_'. $_id. ' start',
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
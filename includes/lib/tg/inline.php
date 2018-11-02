<?php
namespace lib\tg;
use \dash\social\telegram\tg as bot;


class inline
{
	public static function search()
	{
		bot::ok();
		self::inSurveyList();
	}


	public static function inSurveyList()
	{

		$siteTitle  = T_(\dash\option::config('site', 'title'));
		$siteSlogan = T_(\dash\option::config('site', 'slogan'));

		$msg = "<a href='". bot::website(). "'>".$siteTitle. "</a>". "\n";
		$msg .= $siteSlogan. "\n\n";
		$msg .= T_(\dash\option::config('site', 'desc')). "\n";
		$msg .= bot::website();

		$resultInline =
		[
			'results' =>
			[
				[
					'type'                  => 'article',
					'id'                    => 1,
					'title'                 => T_('About'). ' '. $siteTitle ,
					'description'           => $siteSlogan,
					'thumb_url'             =>\dash\url::site().'/static/images/logo.png',
					'input_message_content' =>
					[
						'message_text' => $msg,
						'parse_mode'   => 'html'
					],
					'reply_markup' =>
					[
						'inline_keyboard' =>
						[
							[
								[
									'text' => T_("Open :val website", ['val' => $siteTitle]),
									'url'  => bot::website(),
								],
							],
							[
								[
									'text' => T_(":val Telegram bot", ['val' => $siteTitle]),
									'url'  => 'https://t.me/'. bot::$name,
								],
							]
						]
					],
				]
			]
		];

		bot::answerInlineQuery($resultInline);

	}
}
?>
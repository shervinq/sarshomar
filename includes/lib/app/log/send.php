<?php
namespace lib\app\log;


class send
{

	public static function dataArray($_args)
	{
		$data = isset($_args['data']) ? $_args['data'] : [];

		if(is_string($data) && (substr($data, 0, 1) === '{' || substr($data, 0, 1) === '['))
		{
			$data = json_decode($data, true);
		}

		return $data;
	}


	public static function surveyPublished($_args, $_user)
	{

		$data = self::dataArray($_args);

		$ttitle   = isset($data['ttitle']) ? $data['ttitle'] : null;

		$msg                = [];
		$msg['title']       = T_("Your survey was published");
		$msg['content']     = T_("Your survey was published");

		$msg['telegram']    = true;

		$code = (isset($_args['code']) ? $_args['code']: null);

		$tg_msg = '🎉';
		$tg_msg .= "\n #SurveyPublished";
		if($code)
		{
			$tg_msg .= " /s_". $code. "\n";
		}

		if($ttitle)
		{
			$tg_msg .= T_("The survey"). " ";
			$tg_msg .= $ttitle;
			$tg_msg .= T_("Was published");
		}
		else
		{
			$tg_msg .= T_("Your survey was published");
		}

		if(isset($_args['datecreated']))
		{
			$tg_msg .= "\n⏳ ". \dash\datetime::fit($_args['datecreated'], true);
		}

		$msg['send_msg']             = [];
		$msg['send_msg']['telegram'] = $tg_msg;

		$msg['btn']                  = [];
		// $msg['btn']['telegram']      =
		// [
		// 	'reply_markup'           =>
		// 	[
		// 		'inline_keyboard'    =>
		// 		[
		// 			[
		// 				[
		// 					'text' => 	T_("Visit in site"),
		// 					'url'  => \dash\url::base(). '/!'. $code,
		// 				],
		// 			],
		// 			[
		// 				[
		// 					'text'          => 	T_("Check ticket"),
		// 					'callback_data' => 'ticket '. $code,
		// 				],
		// 			],
		// 			[
		// 				[
		// 					'text'          => 	T_("Answer"),
		// 					'callback_data' => 'ticket '. $code. ' answer',
		// 				],
		// 			],
		// 		],
		// 	],
		// ];

		return $msg;
	}


}
?>
<?php
namespace lib\tg;


class questionSender
{
	public static function analyse($_questionData)
	{
		var_dump($_questionData);
		exit();

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





		$txt_text = T_("Hello This is question one");
		// empty keyboard
		$result =
		[
			'text'         => $txt_text,
			'reply_markup' =>
			[
				'keyboard' => [[T_('Cancel')]],
				'resize_keyboard' => true,
				'one_time_keyboard' => true
			],
		];
		bot::sendMessage($result);
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



}
?>
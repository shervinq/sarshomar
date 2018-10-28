<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;


class questionSender
{
	public static function analyse($_questionData)
	{
		// get message body
		$text         = self::body($_questionData);
		$reply_markup = null;


		switch ($_questionData['type'])
		{
			case 'short_answer':
				self::shortAnswer($_questionData, $text, $reply_markup);
				break;

			case 'multiple_choice':
				// self::multiple_choice($_questionData, $text, $reply_markup);
				break;


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
				bot::sendMessage(T_('This type of message is not supported!'));
				return false;
				break;
		}

		// generate result
		$result =
		[
			'text'         => $text,
			'reply_markup' => $reply_markup
		];
		// send message
		bot::sendMessage($result);
	}


	private static function body($_questionData)
	{
		$bodyTxt = '';
		if(isset($_questionData['title']))
		{
			$bodyTxt .= "❔ <b>". $_questionData['title']. "</b>\n\n";
		}

		if(isset($_questionData['desc']))
		{
			$temp = $_questionData['desc'];
			$temp = str_replace('&nbsp;', ' ', $temp);
			$temp = str_replace('</p>', "</p>\n", $temp);
			$temp = strip_tags($temp, '<br><b>');
			$bodyTxt .= $temp;
		}

		if(isset($_questionData['media']['file']))
		{
			$bodyTxt .= "\n". "<a href='". $_questionData['media']['file']. "'>". T_("Image"). "</a>";
		}

		return $bodyTxt;
	}


	private static function shortAnswer($_question, &$_txt, &$_kbd)
	{


	}









	// private static function multiple_choice()
	// {
	// 	$question = \dash\data::question();
	// 	$msg = '';
	// 	if(isset($question['choice']) && is_array($question['choice']))
	// 	{
	// 		foreach ($question['choice'] as $key => $choice)
	// 		{
	// 			if(isset($choice['title']))
	// 			{
	// 				$msg .= $key . ': '. $choice['title']. "\n";

	// 			}
	// 		}

	// 	}
	// 	return $msg;
	// }



}
?>
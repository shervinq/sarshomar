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
		$reply_markup = false;


		switch ($_questionData['type'])
		{
			case 'short_answer':
				self::short_answer($_questionData, $text, $reply_markup);
				break;

			case 'descriptive_answer':
				self::descriptive_answer($_questionData, $text, $reply_markup);
				break;

			case 'numeric':
			case 'rangeslider':
				self::numeric($_questionData, $text, $reply_markup);
				break;

			case 'date':
				self::date($_questionData, $text, $reply_markup);
				break;

			case 'time':
				self::time($_questionData, $text, $reply_markup);
				break;

			case 'mobile':
				self::mobile($_questionData, $text, $reply_markup);
				break;

			case 'email':
				self::email($_questionData, $text, $reply_markup);
				break;

			case 'website':
				self::website($_questionData, $text, $reply_markup);
				break;

			case 'single_choice':
			case 'dropdown':
				self::single_choice($_questionData, $text, $reply_markup);
				break;

			case 'multiple_choice':
				self::multiple_choice($_questionData, $text, $reply_markup);
				break;

			case 'rating':
				self::rating($_questionData, $text, $reply_markup);
				break;

			default:
				// not support this type
				bot::sendMessage(T_('This type of message is not supported!'). $_questionData['type']);
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
			$bodyTxt .= "❔";
			$bodyTxt .= " <b>". $_questionData['title']. "</b>";
			// add require badge
			if(isset($_questionData['require']))
			{
				$bodyTxt .= " <code>*". T_('Require'). "</code>";
			}
			$bodyTxt .= "\n\n";
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


	private static function short_answer($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please wrote short answer for this question');
	}


	private static function descriptive_answer($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please describe your answer');
	}


	private static function numeric($_question, &$_txt, &$_kbd)
	{
		$min = null;
		$max = null;
		if(isset($_question['setting']['numeric']['min']))
		{
			$min = $_question['setting']['numeric']['min'];
		}
		if(isset($_question['setting']['numeric']['max']))
		{
			$max = $_question['setting']['numeric']['max'];
		}

		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter number between :min and :max', ['min' => $min, 'max' => $max]);
	}


	private static function date($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter date in format <code>yyyy-mm-dd</code> like <code>2018-10-28</code>');
	}


	private static function time($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter time like <code>19:41</code>');
	}


	private static function mobile($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter mobile number like <code>09350001234</code>');
	}


	private static function email($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter email like <code>abc@example.com</code>');
	}


	private static function website($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter website like <code>jibres.com</code>');
	}


	private static function single_choice($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please choose your answer');

		if(isset($_question['choice']))
		{
			$choices = $_question['choice'];
			if(is_array($choices) && $choices)
			{
				$_kbd =
				[
					'inline_keyboard' => []
				];

				foreach ($choices as $key => $value)
				{
					if(isset($value['title']))
					{
						$_kbd['inline_keyboard'][][] =
						[
							'text' => $value['title'],
							'callback_data' => 'survey_123 '. $value['title'],
						];
					}
				}

			}
		}
	}


	private static function multiple_choice($_question, &$_txt, &$_kbd)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please choose your answer');

		if(isset($_question['choice']))
		{
			$choices = $_question['choice'];
			if(is_array($choices) && $choices)
			{
				$_kbd =
				[
					'inline_keyboard' => []
				];

				foreach ($choices as $key => $value)
				{
					if(isset($value['title']))
					{
						$_kbd['inline_keyboard'][][] =
						[
							'text' => $value['title'],
							'callback_data' => 'survey_123 '. $value['title'],
						];
					}
				}

			}
		}
	}


	private static function rating($_question, &$_txt, &$_kbd)
	{
		$_txt      .= "\n\n";
		$_txt      .= '❇️ '. T_('Please your rate');
		$max       = 5;
		$rateEmoji = '⭐️';

		if(isset($_question['setting']['rating']['max']))
		{
			$max = $_question['setting']['rating']['max'];
		}

		if(isset($_question['setting']['rating']['ratetype']))
		{
			switch ($_question['setting']['rating']['ratetype'])
			{
				case 'star':
					$rateEmoji = '⭐️';
					break;

				case 'heart':
					$rateEmoji = '❤️';
					break;

				case 'bell':
					$rateEmoji = '🔔';
					break;

				case 'flag':
					$rateEmoji = '🏁';
					break;

				case 'bookmark':
					$rateEmoji = '📎';
					break;

				case 'like':
					$rateEmoji = '👍';
					break;

				case 'dislike':
					$rateEmoji = '👎';
					break;

				case 'user1':
					$rateEmoji = '👤';
					break;
			}
		}


		$_kbd =
		[
			'inline_keyboard' => []
		];

		for ($i=0; $i < $max; $i++)
		{
			$_kbd['inline_keyboard'][][] =
			[
				'text' => str_repeat($rateEmoji, $i),
				'callback_data' => 'survey_123 '. 'rating'.$i,
			];
		}
	}


}
?>
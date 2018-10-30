<?php
namespace lib\tg;
// use telegram class as bot
use \dash\social\telegram\tg as bot;


class questionSender
{
	public static function analyse($_questionData, $_answer)
	{
		// get message body
		$text         = self::body($_questionData, $_answer);
		$reply_markup = false;


		switch ($_questionData['type'])
		{
			case 'short_answer':
				self::short_answer($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'descriptive_answer':
				self::descriptive_answer($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'numeric':
			case 'rangeslider':
				self::numeric($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'date':
				self::date($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'time':
				self::time($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'mobile':
				self::mobile($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'email':
				self::email($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'website':
				self::website($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'single_choice':
			case 'dropdown':
				self::single_choice($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'multiple_choice':
				self::multiple_choice($_questionData, $text, $reply_markup, $_answer);
				break;

			case 'rating':
				self::rating($_questionData, $text, $reply_markup, $_answer);
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
		if($_questionData['type'] === 'multiple_choice')
		{
			bot::editMessageText($result);
		}
		else
		{
			// send message
			bot::sendMessage($result);
		}
	}


	private static function body($_questionData, $_answer)
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

		// get user answer list
		if($_answer)
		{
			$bodyTxt .= "\n☑️ ". T_('Your answer');
			if(count($_answer) > 1)
			{
				$bodyTxt .= "\n<pre>". implode("\n", $_answer). "</pre>";
			}
			else
			{
				$bodyTxt .= " <code>". implode(T_(', '), $_answer). "</code>";
			}
		}
		return $bodyTxt;
	}


	private static function short_answer($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please wrote short answer for this question');
	}


	private static function descriptive_answer($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please describe your answer');
	}


	private static function numeric($_question, &$_txt, &$_kbd, $_answer)
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


	private static function date($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter date in format <code>yyyy-mm-dd</code> like <code>2018-10-28</code>');
	}


	private static function time($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter time like <code>19:41</code>');
	}


	private static function mobile($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter mobile number like <code>09350001234</code>');
	}


	private static function email($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter email like <code>abc@example.com</code>');
	}


	private static function website($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please enter website like <code>jibres.com</code>');
	}


	private static function single_choice($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please choose your answer');

		$surveyId   = null;
		$questionId = null;
		if(isset($_question['survey_id']))
		{
			$surveyId = $_question['survey_id'];
		}
		if(isset($_question['id']))
		{
			$questionId = $_question['id'];
		}

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
						$itemTitle    = $value['title'];
						$itemId       = $value['title'];
						$selectedMark = '';

						if(isset($value['id']) && $value['id'])
						{
							$itemId = $value['id'];
						}
						if(in_array($itemTitle, $_answer))
						{
							$selectedMark = '☑️ ';
						}

						$_kbd['inline_keyboard'][][] =
						[
							'text' => $selectedMark. $value['title'],
							'callback_data' => 'survey_'. $surveyId. ' '. $questionId. ' '. $itemId,
						];
					}
				}

			}
		}
	}


	private static function multiple_choice($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt .= "\n\n";
		$_txt .= '❇️ '. T_('Please choose your answer');

		$surveyId   = null;
		$questionId = null;
		if(isset($_question['survey_id']))
		{
			$surveyId = $_question['survey_id'];
		}
		if(isset($_question['id']))
		{
			$questionId = $_question['id'];
		}

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
						$itemTitle    = $value['title'];
						$itemId       = $value['title'];
						$selectedMark = '';

						if(isset($value['id']) && $value['id'])
						{
							$itemId = $value['id'];
						}
						if(in_array($itemTitle, $_answer))
						{
							$selectedMark = '☑️ ';
						}

						$_kbd['inline_keyboard'][][] =
						[
							'text' => $selectedMark. $itemTitle,
							'callback_data' => 'survey_'. $surveyId. ' '. $questionId. ' '. $itemId,
						];
					}
				}

				// if($_answer)
				{
					$_kbd['inline_keyboard'][][] =
					[
						'text'          => T_('Save and next'),
						'callback_data' => 'survey_'. $surveyId. ' '. $questionId. '  /save',
					];

				}
			}
		}
	}


	private static function rating($_question, &$_txt, &$_kbd, $_answer)
	{
		$_txt      .= "\n\n";
		$_txt      .= '❇️ '. T_('Please choose your rate');
		$max       = 5;
		$rateEmoji = '⭐️';

		$surveyId   = null;
		$questionId = null;
		if(isset($_question['survey_id']))
		{
			$surveyId = $_question['survey_id'];
		}
		if(isset($_question['id']))
		{
			$questionId = $_question['id'];
		}

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
			$itemTitle    = $value['title'];
			$itemId       = $value['title'];
			$selectedMark = '';

			if(isset($value['id']) && $value['id'])
			{
				$itemId = $value['id'];
			}
			if(in_array($i, $_answer))
			{
				$selectedMark = '☑️ ';
			}


			$_kbd['inline_keyboard'][][] =
			[
				'text' => str_repeat($rateEmoji, $i),
				'callback_data' => 'survey_'. $surveyId. ' '. $questionId. ' '. $i,
			];
		}
	}


}
?>
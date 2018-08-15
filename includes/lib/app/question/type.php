<?php
namespace lib\app\question;


trait type
{

	public static function get_type($_key, $_index = null)
	{
		$list = self::all_type();

		if(array_key_exists($_key, $list))
		{
			if($_index)
			{
				if(array_key_exists($_index, $list[$_key]))
				{
					return $list[$_key][$_index];
				}
				else
				{
					return null;
				}
			}
			else
			{
				return $list[$_key];
			}
		}

		return null;
	}


	public static function all_type()
	{
		// sample array
		// $sample =
		// [
		// 	'key'           => 'picture_choice',
		//  'placeholder'   => true,
		// 	'title'         => T_('Picture choice'),
		// 	'choice'        => true,
		// 	'random'        => true,
		// 	'min'           => false,
		// 	'max'           => false,
		// 	'otherchoice'   => false,
		// 	'chart'         => false,
		// 	'prifile_field' => null,
		// 	'maxchar'       => false,
		// 	'minchoice'     => false,
		// 	'maxchoice'     => false,
		// 	'choiceinline'  => false,
		// 	'label3'         => false,
		// 	'desc'          => T_('Picture choice'),
		// 	'logo'          => \dash\url::site(). '/static/images/logo.png',
		// 	'upload_choice' => true,
		// ];

		$type = [];

		$type['short_answer'] =
		[
			'key'           => 'short_answer',
			'title'         => T_("Short answer"),
			'placeholder'   => true,
			'maxchar'       => true,
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['descriptive_answer'] =
		[
			'key'           => 'descriptive_answer',
			'placeholder'   => true,
			'title'         => T_('Descriptive answer'),
			'maxchar'       => true,
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['numeric'] =
		[
			'key'           => 'numeric',
			'placeholder'   => true,
			'title'         => T_('Numberic'),
			'min'           => true,
			'max'           => true,
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['single_choice'] =
		[
			'key'           => 'single_choice',
			'title'         => T_('Single choice'),
			'choice'        => true,
			'choiceinline'  => true,
			'random'        => true,
			'chart'         => true,
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['multiple_choice'] =
		[
			'key'           => 'multiple_choice',
			'title'         => T_('Multiple choice'),
			'choice'        => true,
			'random'        => true,
			'chart'         => true,
			'minchoice'     => true,
			'maxchoice'     => true,
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['dropdown'] =
		[
			'key'           => 'dropdown',
			'title'         => T_('Dropdown'),
			'placeholder'   => true,
			'choice'        => true,
			'chart'         => true,
			'random'        => true,
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['date'] =
		[
			'key'           => 'date',
			'placeholder'   => true,
			'title'         => T_('Date'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['email'] =
		[
			'key'         => 'email',
			'placeholder' => true,
			'title'       => T_('Email'),
			'logo'        => \dash\url::site(). '/static/images/logo.png',
		];

		$type['website'] =
		[
			'key'         => 'website',
			'placeholder' => true,
			'title'       => T_('Website'),
			'maxchar'     => true,
			'logo'        => \dash\url::site(). '/static/images/logo.png',
		];


		$type['rating'] =
		[
			'key'     => 'rating',
			'title'   => T_('Rating'),
			'maxrate' => true,
			'logo'    => \dash\url::site(). '/static/images/logo.png',
		];

		$type['rangeslider'] =
		[
			'key'    => 'rangeslider',
			'title'  => T_('Range slider'),
			'max'    => true,
			'min'    => true,
			'lable3' => true,
			'logo'   => \dash\url::site(). '/static/images/logo.png',
		];


		// card_descign

		// file_upload


		return $type;
	}
}
?>
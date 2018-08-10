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
		// 	'title'         => T_('Picture choice'),
		// 	'choice'        => true,
		// 	'random'        => true,
		// 	'validation'    => null,
		// 	'otherchoice'   => false,
		// 	'prifile_field' => null,
		// 	'maxchar'       => false,
		// 	'desc'          => T_('Picture choice'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// 	'upload_choice' => true,
		// ];

		$type = [];

		$type['short_answer'] =
		[
			'key'           => 'short_answer',
			'title'         => T_("Short answer"),
			'maxchar'       => true,
			// 'desc'          => T_("Short text"),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['descriptive_answer'] =
		[
			'key'           => 'descriptive_answer',
			'title'         => T_('Descriptive answer'),
			'maxchar'       => true,
			// 'desc'          => T_('Long text'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		$type['numeric'] =
		[
			'key'           => 'numeric',
			'title'         => T_('Numberic'),
			'validation'    => 'numeric',
			'maxchar'       => true,
			// 'desc'          => T_('Number'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['single_choice'] =
		[
			'key'           => 'single_choice',
			'title'         => T_('Single choice'),
			'choice'        => true,
			'random'        => true,
			'otherchoice'   => true,
			'maxchar'       => true,
			// 'desc'          => T_('Single choice'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['multiple_choice'] =
		[
			'key'           => 'multiple_choice',
			'title'         => T_('Multiple choice'),
			'choice'        => true,
			'random'        => true,
			'otherchoice'   => true,
			'maxchar'       => true,
			// 'desc'          => T_('Multiple choice'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['dropdown'] =
		[
			'key'           => 'dropdown',
			'title'         => T_('Dropdown'),
			'choice'        => true,
			'random'        => true,
			'otherchoice'   => true,
			'maxchar'       => true,
			// 'desc'          => T_('Dropdown'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];


		// $type['card_descign'] =
		// [
		// 	'key'           => 'card_descign',
		// 	'title'         => T_('Card design'),
		// 	'choice'        => true,
		// 	'random'        => true,
		// 	// 'desc'          => T_('Picture choice'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// 	'upload_choice' => true,
		// ];


		$type['confirm'] =
		[
			'key'           => 'confirm',
			'title'         => T_('Confirm buttom'),
			'maxchar'       => true,
			// 'desc'          => T_('Confirm buttom'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['date'] =
		[
			'key'           => 'date',
			'title'         => T_('Date'),
			'validation'    => 'date',
			'maxchar'       => true,
			// 'desc'          => T_('Date'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['email'] =
		[
			'key'           => 'email',
			'title'         => T_('Email'),
			'maxchar'       => true,
			// 'desc'          => T_('Email'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['website'] =
		[
			'key'           => 'website',
			'title'         => T_('Website'),
			'maxchar'       => true,
			// 'desc'          => T_('Website'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		// $type['rating'] =
		// [
		// 	'key'           => 'rating',
		// 	'title'         => T_('Rating'),
		// 	'maxchar'       => true,
		// 	// 'desc'          => T_('Scale'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['star'] =
		// [
		// 	'key'           => 'star',
		// 	'title'         => T_('Star'),
		// 	'maxchar'       => true,
		// 	// 'desc'          => T_('Rating'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];


		// $type['file_upload'] =
		// [
		// 	'key'           => 'file_upload',
		// 	'title'         => T_('File upload'),
		// 	// 'desc'          => T_('File upload'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		return $type;
	}
}
?>
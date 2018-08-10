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

		// $type['short_text'] =
		// [
		// 	'key'           => 'short_text',
		// 	'title'         => T_("Short text"),
		// 	'maxchar'       => true,
		// 	'desc'          => T_("Short text"),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['long_text'] =
		// [
		// 	'key'           => 'long_text',
		// 	'title'         => T_('Long text'),
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Long text'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		$type['single_choice'] =
		[
			'key'           => 'single_choice',
			'title'         => T_('Single choice'),
			'choice'        => true,
			'random'        => true,
			'otherchoice'   => true,
			'maxchar'       => true,
			'desc'          => T_('Single choice'),
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
			'desc'          => T_('Multiple choice'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		// $type['picture_choice'] =
		// [
		// 	'key'           => 'picture_choice',
		// 	'title'         => T_('Picture choice'),
		// 	'choice'        => true,
		// 	'random'        => true,
		// 	'desc'          => T_('Picture choice'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// 	'upload_choice' => true,
		// ];

		$type['dropdown'] =
		[
			'key'           => 'dropdown',
			'title'         => T_('Dropdown'),
			'choice'        => true,
			'random'        => true,
			'otherchoice'   => true,
			'maxchar'       => true,
			'desc'          => T_('Dropdown'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		// $type['yes_no'] =
		// [
		// 	'key'           => 'yes_no',
		// 	'title'         => T_('yes/no'),
		// 	'random'        => true,
		// 	'desc'          => T_('yes/no'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['legal'] =
		// [
		// 	'key'           => 'legal',
		// 	'title'         => T_('Legal'),
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Legal'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['email'] =
		// [
		// 	'key'           => 'email',
		// 	'title'         => T_('Email'),
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Email'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['scale'] =
		// [
		// 	'key'           => 'scale',
		// 	'title'         => T_('Scale'),
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Scale'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['rating'] =
		// [
		// 	'key'           => 'rating',
		// 	'title'         => T_('Rating'),
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Rating'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['date'] =
		// [
		// 	'key'           => 'date',
		// 	'title'         => T_('Date'),
		// 	'validation'    => 'date',
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Date'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		$type['number'] =
		[
			'key'           => 'number',
			'title'         => T_('Number'),
			'validation'    => 'number',
			'maxchar'       => true,
			'desc'          => T_('Number'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		// $type['file_upload'] =
		// [
		// 	'key'           => 'file_upload',
		// 	'title'         => T_('File upload'),
		// 	'desc'          => T_('File upload'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		// $type['website'] =
		// [
		// 	'key'           => 'website',
		// 	'title'         => T_('Website'),
		// 	'maxchar'       => true,
		// 	'desc'          => T_('Website'),
		// 	'logo'			=> \dash\url::site(). '/static/images/logo.png',
		// ];

		return $type;
	}
}
?>
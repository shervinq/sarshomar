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
		$type = [];

		$type['short_text'] =
		[
			'key'           => 'short_text',
			'title'         => T_("Short text"),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_("Short text"),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['long_text'] =
		[
			'key'           => 'long_text',
			'title'         => T_('Long text'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Long text'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['single_choise'] =
		[
			'key'           => 'single_choise',
			'title'         => T_('Single choise'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
			'random'        => true,
			'otherchoise'   => true,
			'maxchar'       => true,
			'desc'          => T_('Single choise'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['multiple_choise'] =
		[
			'key'           => 'multiple_choise',
			'title'         => T_('Multiple choise'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
			'random'        => true,
			'otherchoise'   => true,
			'maxchar'       => true,
			'desc'          => T_('Multiple choise'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['picture_choice'] =
		[
			'key'           => 'picture_choice',
			'title'         => T_('Picture choise'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
			'random'        => true,
			'otherchoise'   => false,
			'maxchar'       => false,
			'desc'          => T_('Picture choise'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['dropdown'] =
		[
			'key'           => 'dropdown',
			'title'         => T_('Dropdown'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
			'random'        => true,
			'otherchoise'   => true,
			'maxchar'       => true,
			'desc'          => T_('Dropdown'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['yes_no'] =
		[
			'key'           => 'yes_no',
			'title'         => T_('yes/no'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => true,
			'otherchoise'   => false,
			'maxchar'       => false,
			'desc'          => T_('yes/no'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['legal'] =
		[
			'key'           => 'legal',
			'title'         => T_('Legal'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Legal'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['email'] =
		[
			'key'           => 'email',
			'title'         => T_('Email'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Email'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['scale'] =
		[
			'key'           => 'scale',
			'title'         => T_('Scale'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Scale'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['rating'] =
		[
			'key'           => 'rating',
			'title'         => T_('Rating'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Rating'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['date'] =
		[
			'key'           => 'date',
			'title'         => T_('Date'),
			'choise'        => false,
			'validation'    => 'date',
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Date'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['number'] =
		[
			'key'           => 'number',
			'title'         => T_('Number'),
			'choise'        => false,
			'validation'    => 'number',
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Number'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['file_upload'] =
		[
			'key'           => 'file_upload',
			'title'         => T_('File upload'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => false,
			'desc'          => T_('File upload'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		$type['website'] =
		[
			'key'           => 'website',
			'title'         => T_('Website'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
			'random'        => false,
			'otherchoise'   => false,
			'maxchar'       => true,
			'desc'          => T_('Website'),
			'logo'			=> \dash\url::site(). '/static/images/logo.png',
		];

		return $type;
	}
}
?>
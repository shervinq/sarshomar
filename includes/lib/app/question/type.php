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
		];

		$type['long_text'] =
		[
			'key'           => 'long_text',
			'title'         => T_('Long text'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['single_choise'] =
		[
			'key'           => 'single_choise',
			'title'         => T_('Single choise'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['multiple_choise'] =
		[
			'key'           => 'multiple_choise',
			'title'         => T_('Multiple choise'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['picture_choice'] =
		[
			'key'           => 'picture_choice',
			'title'         => T_('Picture choise'),
			'choise'        => true,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['yes_no'] =
		[
			'key'           => 'yes_no',
			'title'         => T_('yes/no'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['legal'] =
		[
			'key'           => 'legal',
			'title'         => T_('Legal'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['email'] =
		[
			'key'           => 'email',
			'title'         => T_('Email'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['scale'] =
		[
			'key'           => 'scale',
			'title'         => T_('Scale'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['rating'] =
		[
			'key'           => 'rating',
			'title'         => T_('Rating'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['date'] =
		[
			'key'           => 'date',
			'title'         => T_('Date'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['number'] =
		[
			'key'           => 'number',
			'title'         => T_('Number'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['dropdown'] =
		[
			'key'           => 'dropdown',
			'title'         => T_('Dropdown'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['fileid'] =
		[
			'key'           => 'fileid',
			'title'         => T_('Fileid'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type['website'] =
		[
			'key'           => 'website',
			'title'         => T_('Website'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		return $type;
	}
}
?>
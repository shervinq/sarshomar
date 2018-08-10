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
		];

		return $type;
	}
}
?>
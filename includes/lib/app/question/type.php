<?php
namespace lib\app\question;


trait type
{

	public static function all_type()
	{
		$type = [];

		$type[] =
		[
			'key'           => 'short_text',
			'title'         => T_("Short text"),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'long_text',
			'title'         => T_('Long text'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'single_choise',
			'title'         => T_('Single choise'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'mutli_choise',
			'title'         => T_('Multiple choise'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'picture_choice',
			'title'         => T_('Picture choise'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'yes_no',
			'title'         => T_('yes/no'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'legal',
			'title'         => T_('Legal'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'email',
			'title'         => T_('Email'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'scale',
			'title'         => T_('Scale'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'rating',
			'title'         => T_('Rating'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'date',
			'title'         => T_('Date'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'number',
			'title'         => T_('Number'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'dropdown',
			'title'         => T_('Dropdown'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
		[
			'key'           => 'fileid',
			'title'         => T_('Fileid'),
			'choise'        => false,
			'validation'    => null,
			'profile_field' => null,
		];

		$type[] =
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
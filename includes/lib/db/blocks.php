<?php
namespace lib\db;


class blocks
{

	public static function insert()
	{
		\dash\db\config::public_insert('blocks', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function update()
	{
		return \dash\db\config::public_update('blocks', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('blocks', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		$default_option =
		[
			'search_field' => " (title LIKE '%__string__%' ) ",
		];

		$_option = array_merge($default_option, $_option);
		return \dash\db\config::public_search('blocks', $_string, $_option);
	}


	public static function get_count()
	{
		return \dash\db\config::public_get_count('blocks', ...func_get_args());
	}

}
?>
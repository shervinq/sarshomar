<?php
namespace lib\db;


class questions
{

	public static function insert()
	{
		\dash\db\config::public_insert('questions', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function update()
	{
		return \dash\db\config::public_update('questions', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('questions', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		$default_option =
		[
			'search_field' => " (title LIKE '%__string__%' ) ",
		];

		$_option = array_merge($default_option, $_option);
		return \dash\db\config::public_search('questions', $_string, $_option);
	}


	public static function get_count()
	{
		return \dash\db\config::public_get_count('questions', ...func_get_args());
	}

}
?>
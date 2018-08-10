<?php
namespace lib\db;


class answers
{

	public static function insert()
	{
		\dash\db\config::public_insert('answers', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function update()
	{
		return \dash\db\config::public_update('answers', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('answers', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		$default_option =
		[
			'search_field' => " (title LIKE '%__string__%' ) ",
		];

		$_option = array_merge($default_option, $_option);
		return \dash\db\config::public_search('answers', $_string, $_option);
	}


	public static function get_count()
	{
		return \dash\db\config::public_get_count('answers', ...func_get_args());
	}

}
?>
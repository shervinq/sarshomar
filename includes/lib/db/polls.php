<?php
namespace lib\db;


class polls
{

	public static function insert()
	{
		\dash\db\config::public_insert('polls', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function update()
	{
		return \dash\db\config::public_update('polls', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('polls', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		$default_option =
		[
			'search_field' => " (title LIKE '%__string__%' ) ",
		];

		$_option = array_merge($default_option, $_option);
		return \dash\db\config::public_search('polls', $_string, $_option);
	}


	public static function get_count()
	{
		return \dash\db\config::public_get_count('polls', ...func_get_args());
	}

}
?>
<?php
namespace lib\db;


class answerdetails
{

	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('answerdetails', ...func_get_args());
	}


	public static function insert()
	{
		\dash\db\config::public_insert('answerdetails', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function delete_where()
	{
		return \dash\db\config::public_delete_where('answerdetails', ...func_get_args());
	}


	public static function update()
	{
		return \dash\db\config::public_update('answerdetails', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('answerdetails', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		$default_option =
		[
			'search_field' => " (title LIKE '%__string__%' ) ",
		];

		$_option = array_merge($default_option, $_option);
		return \dash\db\config::public_search('answerdetails', $_string, $_option);
	}


	public static function get_count()
	{
		return \dash\db\config::public_get_count('answerdetails', ...func_get_args());
	}

}
?>
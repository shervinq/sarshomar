<?php
namespace lib\db;


class surveys
{
	public static function get_all_status()
	{
		$query = "SELECT COUNT(*) AS `count`, surveys.status AS `status` FROM surveys GROUP BY surveys.status";
		$result =  \dash\db::get($query, ['status', 'count']);

		return $result;
	}


	public static function update_countblock($_survery_id)
	{
		$query = "UPDATE surveys SET surveys.countblock = (SELECT COUNT(*) FROM questions WHERE questions.survey_id = $_survery_id AND questions.status != 'deleted') WHERE surveys.id = $_survery_id LIMIT 1";
		return \dash\db::query($query);
	}


	public static function plus_field()
	{
		return \dash\db\config::public_plus_field('surveys', ...func_get_args());
	}


	public static function insert()
	{
		\dash\db\config::public_insert('surveys', ...func_get_args());
		return \dash\db::insert_id();
	}


	public static function update()
	{
		return \dash\db\config::public_update('surveys', ...func_get_args());
	}


	public static function get()
	{
		return \dash\db\config::public_get('surveys', ...func_get_args());
	}


	public static function search($_string = null, $_option = [])
	{
		if(isset($_option['join_creator']))
		{
			$default_option =
			[
				'search_field' => " (surveys.title LIKE '%__string__%' ) ",
				'public_show_field' =>
				"
					surveys.*,
					(SELECT COUNT(*) FROM answers WHERE answers.survey_id = surveys.id) AS `answer_count`,
					users.gender AS `user_gender`,
					users.firstname AS `user_firstname`,
					users.lastname AS `user_lastname`,
					users.displayname AS `user_displayname`,
					users.chatid AS `user_chatid`


				",
				'master_join' => " INNER JOIN users ON users.id = surveys.user_id ",
			];
		}
		else
		{
			$default_option =
			[
				'search_field' => " (title LIKE '%__string__%' ) ",
				'public_show_field' => " surveys.*, (SELECT COUNT(*) FROM answers WHERE answers.survey_id = surveys.id) AS `answer_count` ",
			];
		}

		unset($_option['join_creator']);

		$_option = array_merge($default_option, $_option);
		$result =  \dash\db\config::public_search('surveys', $_string, $_option);

		return $result;
	}


	public static function get_count()
	{
		return \dash\db\config::public_get_count('surveys', ...func_get_args());
	}

}
?>
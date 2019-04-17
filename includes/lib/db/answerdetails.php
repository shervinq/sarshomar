<?php
namespace lib\db;


class answerdetails
{

	public static function multi_insert()
	{
		return \dash\db\config::public_multi_insert('answerdetails', ...func_get_args());
	}


	public static function get_user_score($_survey_id, $_user_id)
	{
		if(!$_user_id || !$_survey_id || !is_numeric($_survey_id) || !is_numeric($_user_id))
		{
			return false;
		}

		$query = "SELECT SUM(answerdetails.score) AS `score` FROM answerdetails WHERE answerdetails.survey_id = $_survey_id AND answerdetails.user_id = $_user_id";
		$result = \dash\db::get($query, 'score', true);
		return intval($result);
	}

	public static function get_user_answer($_survey_id, $_user_id, $_question_id)
	{
		if(!$_user_id || !$_survey_id || !is_numeric($_survey_id) || !is_numeric($_user_id) || !$_question_id || !is_numeric($_question_id))
		{
			return false;
		}

		$query =
		"
			SELECT answerterms.text AS `text` FROM answerterms
			INNER JOIN answerdetails ON answerdetails.answerterm_id = answerterms.id
			WHERE answerdetails.survey_id = $_survey_id AND answerdetails.user_id = $_user_id AND answerdetails.question_id = $_question_id
		";

		$result = \dash\db::get($query, 'text');
		return implode(' , ', $result);

	}


	public static function get_join($_where, $_option = [])
	{
		$default_option =
		[
			'for_export' => false,
			'public_show_field' =>
			"
				questions.id AS `question_id`,
				answers.startdate,
				answers.enddate,
				answers.lastmodified,
				questions.title AS `question_title`,
				questions.desc  AS `question_desc`,
				questions.type  AS `question_type`,
				answerdetails.*,
				answerterms.*,
				answerdetails.id AS `answerdetail_id`
			",
			'master_join'       =>
			"
				LEFT JOIN answerterms ON answerterms.id = answerdetails.answerterm_id
				INNER JOIN questions   ON questions.id   = answerdetails.question_id
				INNER JOIN surveys     ON surveys.id     = questions.survey_id
				INNER JOIN answers     ON answers.id     = answerdetails.answer_id
			",
		];

		if(!is_array($_option))
		{
			$_option = [];
		}

		$_option = array_merge($default_option, $_option);

		if($_option['for_export'])
		{
			$_option['public_show_field'] =
			"
				answers.id AS `answer_id`,
				questions.id AS `question_id`,
				questions.title AS `question_title`,
				answerterms.text,
				answerdetails.user_id,
				answers.startdate,
				answers.enddate
			";
		}

		return self::get($_where, $_option);
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

			'public_show_field' =>
			"
				questions.title AS `question_title`,
				questions.desc  AS `question_desc`,
				questions.type  AS `question_type`,
				answerdetails.*,
				answerterms.*,
				answerdetails.id AS `answerdetail_id`
			",
			'master_join'       =>
			"
				INNER JOIN answerterms ON answerterms.id = answerdetails.answerterm_id
				INNER JOIN questions   ON questions.id   = answerdetails.question_id
				INNER JOIN surveys     ON surveys.id     = questions.survey_id
			",

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
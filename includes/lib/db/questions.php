<?php
namespace lib\db;


class questions
{
	public static function count_required_question($_survey_id)
	{
		$query =
		"
			SELECT
				COUNT(*) AS `count`
			FROM questions
			WHERE
				questions.survey_id = $_survey_id AND
				questions.require IS NOT NULL AND
				questions.status != 'deleted'
		";
		$result = \dash\db::get($query, 'count', true);
		return $result;
	}

	public static function random_question($_survey_id, $_user_id, $_ids = [])
	{
		$ids_query = null;
		if($_ids)
		{
			$id = implode(',', $_ids);
			$ids_query =  "AND questions.sort IN ($id) ";
		}

		$query =
		"
			SELECT
				*
			FROM questions

			WHERE
				questions.survey_id = $_survey_id AND
				questions.require IS NULL
				$ids_query
			ORDER BY RAND()
			LIMIT 1
		";
		$result = \dash\db::get($query, null, true);
		return $result;
	}

	public static function is_my_question($_survey_id, $_question_id, $_user_id)
	{
		$query =
		"
			SELECT
				*
			FROM questions
			INNER JOIN surveys ON questions.survey_id = surveys.id
			WHERE
				questions.id        = $_question_id AND
				questions.survey_id = $_survey_id AND
				surveys.user_id     = $_user_id
			LIMIT 1
		";
		$result = \dash\db::get($query, null, true);
		return $result;
	}


	public static function get_address($_survey_id)
	{
		if(!$_survey_id || !is_numeric($_survey_id))
		{
			return false;
		}

		$query = "SELECT questions.id AS `id`, questions.address AS `address` FROM questions WHERE questions.survey_id = $_survey_id AND questions.address IS NOT NULL ";
		$result = \dash\db::get($query, ['id', 'address']);
		return $result;
	}


	public static function save_sort($_sort)
	{
		$query = [];
		foreach ($_sort as $key => $value)
		{
			$sort = $key + 1;
			$query[] = " UPDATE questions SET questions.sort = $sort WHERE questions.id = $value LIMIT 1 ";
		}

		$query = implode(';', $query);

		return \dash\db::query($query, true, ['multi_query' => true]);
	}


	public static function get_sort($_where)
	{
		$limit = null;
		$only_one_value = false;
		if(isset($_where['limit']))
		{
			if($_where['limit'] === 1)
			{
				$only_one_value = true;
			}

			$limit = " LIMIT $_where[limit] ";
		}

		unset($_where['limit']);

		$where = \dash\db\config::make_where($_where);
		if($where)
		{
			$query = "SELECT * FROM questions WHERE $where ORDER BY questions.sort ASC, questions.id ASC $limit";
			$result = \dash\db::get($query, null, $only_one_value);
			return $result;
		}
		return false;

	}


	public static function get_sort_chart($_where)
	{
		$where = \dash\db\config::make_where($_where);
		if(!$where)
		{
			return false;
		}

		$result = [];

		$query =
		"
			SELECT
				*
			FROM
				questions
			WHERE
				$where
			ORDER BY questions.sort ASC, questions.id ASC
		";

		$question_list = \dash\db::get($query);

		$result['questions'] = $question_list;

		$ids = [];
		if(is_array($question_list))
		{
			foreach ($question_list as $key => $value)
			{
				if(isset($value['id']) && isset($value['type']) && in_array($value['type'], ['single_choice', 'multiple_choice']))
				{
					$ids[] = $value['id'];
				}
			}
		}


		$ids = array_unique($ids);
		$ids = array_filter($ids);
		if($ids)
		{
			$ids = implode(',', $ids);

			$query_answer =
			"
				SELECT
					answerdetails.question_id,
					answerdetails.answerterm_id,
					MAX(answerterms.text) AS `text`,
					COUNT(*) AS `count`
				FROM
					answerdetails
				INNER JOIN questions ON questions.id = answerdetails.question_id
				JOIN answerterms ON answerterms.id = answerdetails.answerterm_id
				WHERE $where AND answerdetails.question_id IN ($ids)
				GROUP BY
				answerdetails.question_id, answerdetails.answerterm_id
			";

			$chart            = \dash\db::get($query_answer);
			$result['answer'] = $chart;

		}


		return $result;


	}


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

	public static function delete($_question_id)
	{
		if(!$_question_id || !is_numeric($_question_id))
		{
			return false;
		}

		$query = " DELETE FROM questions WHERE questions.id = $_question_id LIMIT 1";
		return \dash\db::query($query);
	}


	public static function get_by_id($_id)
	{
		return self::get(['id' => $_id, 'limit' => 1]);
	}

}
?>
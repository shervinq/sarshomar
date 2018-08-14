<?php
namespace lib\db;


class answers
{

	public static function get_chart($_survey_id, $_question_id, $_user_id)
	{
		$query =
		"
			SELECT
				COUNT(*) AS `count`,
				answerdetails.answerterm_id AS `term`
			FROM
				answerdetails
			WHERE
				answerdetails.survey_id = $_survey_id AND
				answerdetails.question_id = $_question_id
			GROUP BY
				answerdetails.answerterm_id
		";

		$result = \dash\db::get($query, ['term', 'count']);
		$new    = [];
		$term   = [];
		if(is_array($result))
		{
			$answerterm_id = array_keys($result);

			if($answerterm_id)
			{
				$answerterm_id = implode(',', $answerterm_id);
				$query_term = "SELECT * FROM answerterms WHERE answerterms.id IN ($answerterm_id) ";
				$term = \dash\db::get($query_term);
			}

			foreach ($term as $key => $value)
			{
				if(array_key_exists($value['id'], $result))
				{
					$new[] =
					[
						'count'   => $result[$value['id']],
						'term_id' => $value['id'],
						'text'    => $value['text'],
						'file'    => $value['file'],
					];
				}
			}
		}
		return $new;
	}


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
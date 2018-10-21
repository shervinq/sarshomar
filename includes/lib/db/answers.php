<?php
namespace lib\db;


class answers
{

	public static function get_chart($_survey_id, $_question_id, $_user_id, $_sort = null, $_order = null)
	{
		$skip_count  = 0;
		$query_order = null;
		$sort_term   = null;
		if($_sort)
		{
			if($_sort === 'answerdetails.answerterm_id')
			{
				$sort_term = " ORDER BY answerterms.text ";
				if($_order)
				{
					$sort_term .= mb_strtoupper($_order);
				}
			}

			$query_order = " ORDER BY $_sort ";
			if($_order)
			{
				$query_order .= mb_strtoupper($_order);
			}
		}

		if($sort_term)
		{
			$query =
			"
				SELECT
					COUNT(*) AS `count`,
					IF(answerdetails.answerterm_id IS NULL, 'skip', answerdetails.answerterm_id)  AS `term`
				FROM
					answerdetails
				INNER JOIN answers ON answers.id = answerdetails.answer_id
				WHERE
					answers.complete = 1 AND
					answerdetails.survey_id = $_survey_id AND
					answerdetails.question_id = $_question_id
				GROUP BY
					answerdetails.answerterm_id
			";

			$result = \dash\db::get($query, ['term', 'count']);

			if(isset($result['skip']))
			{
				$skip_count = intval($result['skip']);
			}
			unset($result['skip']);
			// $query_all =
			// "
			// 	SELECT
			// 		COUNT(*) AS `count`,
			// 		answerdetails.answerterm_id AS `term`
			// 	FROM
			// 		answerdetails
			// 	INNER JOIN answers ON answers.id = answerdetails.answer_id
			// 	WHERE
			// 		answers.complete IS NULL AND
			// 		answerdetails.survey_id = $_survey_id AND
			// 		answerdetails.question_id = $_question_id
			// 	GROUP BY
			// 		answerdetails.answerterm_id
			// ";

			// $result_all = \dash\db::get($query_all, ['term', 'count']);

			$new    = [];
			$term   = [];
			if(is_array($result))
			{
				$answerterm_id = array_keys($result);
				$answerterm_id = array_filter($answerterm_id);
				$answerterm_id = array_unique($answerterm_id);

				if($answerterm_id)
				{
					$answerterm_id = implode(',', $answerterm_id);
					$query_term = "SELECT * FROM answerterms WHERE answerterms.id IN ($answerterm_id) $sort_term";
					$term = \dash\db::get($query_term);

					foreach ($term as $key => $value)
					{
						if(array_key_exists($value['id'], $result))
						{
							$new[] =
							[
								'count'     => $result[$value['id']],
								// 'count_all' => isset($result_all[$value['id']]) ? $result_all[$value['id']] : 0,
								'term_id'   => $value['id'],
								'text'      => $value['text'],
								'file'      => $value['file'],
							];
						}
					}
				}
			}
		}
		else
		{
			$query =
			"
				SELECT
					COUNT(*) AS `count`,
					IF(answerdetails.answerterm_id IS NULL, 'skip', answerdetails.answerterm_id)  AS `term`
				FROM
					answerdetails
				INNER JOIN answers ON answers.id = answerdetails.answer_id
				WHERE
					answers.complete = 1 AND
					answerdetails.survey_id = $_survey_id AND
					answerdetails.question_id = $_question_id
				GROUP BY
					answerdetails.answerterm_id
				$query_order
			";

			$result = \dash\db::get($query, ['term', 'count']);


			if(isset($result['skip']))
			{
				$skip_count = intval($result['skip']);
			}
			unset($result['skip']);

			// $query_all =
			// "
			// 	SELECT
			// 		COUNT(*) AS `count`,
			// 		answerdetails.answerterm_id AS `term`
			// 	FROM
			// 		answerdetails
			// 	INNER JOIN answers ON answers.id = answerdetails.answer_id
			// 	WHERE
			// 		answers.complete IS NULL AND
			// 		answerdetails.survey_id = $_survey_id AND
			// 		answerdetails.question_id = $_question_id
			// 	GROUP BY
			// 		answerdetails.answerterm_id
			// 	$query_order
			// ";

			// $result_all = \dash\db::get($query_all, ['term', 'count']);
			$new    = [];


			$term   = [];
			if(is_array($result))
			{
				$answerterm_id = array_keys($result);
				$answerterm_id = array_filter($answerterm_id);
				$answerterm_id = array_unique($answerterm_id);

				if($answerterm_id)
				{
					$answerterm_id = implode(',', $answerterm_id);
					$query_term = "SELECT * FROM answerterms WHERE answerterms.id IN ($answerterm_id) $sort_term";
					$term = \dash\db::get($query_term);
					$term = array_combine(array_column($term, 'id'), $term);

					foreach ($result as $key => $value)
					{
						if(isset($term[$key]))
						{
							$new[] =
							[
								'count'     => $value,
								'term_id'   => @$term[$key]['id'],
								'text'      => @$term[$key]['text'],
								'file'      => @$term[$key]['file'],
							];
						}
					}
				}
			}

			if(!$query_order)
			{
				$question_detail = \lib\db\questions::get(['id' => $_question_id, 'limit' => 1]);
				$question_detail = \lib\app\question::ready($question_detail);

				if(isset($question_detail['type']) && in_array($question_detail['type'], ['single_choice','multiple_choice','dropdown']))
				{
					if(isset($question_detail['choice']) && is_array($question_detail['choice']))
					{
						$master_sort = $question_detail['choice'];

						$new2 = [];
						foreach ($master_sort as $base_sort)
						{
							if(!array_key_exists('title', $base_sort))
							{
								continue;
							}

							foreach ($new as $value)
							{
								if(!array_key_exists('text', $value))
								{
									continue;
								}

								if($value['text'] == $base_sort['title'])
								{
									$new2[] = $value;
								}
							}
						}
						$new = $new2;
					}
				}
			}
		}

		if($skip_count)
		{
			$new[] =
			[
				'count'     => $skip_count,
				'term_id'   => null,
				'text'      => T_("Skipped"),
				'file'      => null,
			];
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
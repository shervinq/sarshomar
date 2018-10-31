<?php
namespace lib\app\answer;

class chart
{
	public static function advance_chart($_survey_id, $_question1, $_question2, $_question3, $_args = [])
	{
		$result = \lib\db\answers::advance_chart($_survey_id, $_question1, $_question2, $_question3);

		if(!is_array($result))
		{
			return false;
		}

		$answerterm_id = [];
		$answerterm_id = array_merge($answerterm_id, array_column($result, 'q1'));
		$answerterm_id = array_merge($answerterm_id, array_column($result, 'q2'));
		$answerterm_id = array_merge($answerterm_id, array_column($result, 'q3'));

		$answerterm_id = array_filter($answerterm_id);
		$answerterm_id = array_unique($answerterm_id);
		$answerterm_text = [];
		if(!empty($answerterm_id))
		{
			$answerterm_id = implode(',', $answerterm_id);
			$answerterm_text = \lib\db\answerterms::get(['id' => ["IN", "($answerterm_id)"]]);
			$answerterm_text = array_combine(array_column($answerterm_text, 'id'), array_column($answerterm_text, 'text'));
		}

		$question1_choise = \lib\db\questions::get(['id' => $_question1, 'limit' => 1]);
		$question1_choise = \lib\app\question::ready($question1_choise);
		$question1_choise_title = isset($question1_choise['title']) ? $question1_choise['title'] : null;
		$question1_choise = isset($question1_choise['choice']) ? $question1_choise['choice'] : [];
		$question1_choise = array_column($question1_choise, 'title');
		if(empty($question1_choise))
		{
			\dash\notif::warn(T_("The question 1 is not choiceable"), 'q1');
			return false;
		}

		foreach ($question1_choise as $key => $value)
		{
			$new_key = array_search($value, $answerterm_text);
			if($new_key !== false)
			{
				$question1_choise[$new_key] = $value;
				unset($question1_choise[$key]);
			}
		}

		$question1_choise[0] = T_("Skipped");


		$question2_choise = \lib\db\questions::get(['id' => $_question2, 'limit' => 1]);
		$question2_choise = \lib\app\question::ready($question2_choise);
		$question2_choise_title = isset($question2_choise['title']) ? $question2_choise['title'] : null;
		$question2_choise = isset($question2_choise['choice']) ? $question2_choise['choice'] : [];
		$question2_choise = array_column($question2_choise, 'title');
		if(empty($question2_choise))
		{
			\dash\notif::warn(T_("The question 2 is not choiceable"), 'q2');
			return false;
		}

		foreach ($question2_choise as $key => $value)
		{
			$new_key = array_search($value, $answerterm_text);
			if($new_key !== false)
			{
				$question2_choise[$new_key] = $value;
				unset($question2_choise[$key]);
			}
		}
		$question2_choise[0] = T_("Skipped");

		$question3_choise       = [];
		$question3_choise_title = null;

		if($_question3)
		{
			$question3_choise = \lib\db\questions::get(['id' => $_question3, 'limit' => 1]);
			$question3_choise = \lib\app\question::ready($question3_choise);
			$question3_choise_title = isset($question3_choise['title']) ? $question3_choise['title'] : null;
			$question3_choise = isset($question3_choise['choice']) ? $question3_choise['choice'] : [];
			$question3_choise = array_column($question3_choise, 'title');
			if(empty($question3_choise))
			{
				\dash\notif::warn(T_("The question 3 is not choiceable"), 'q3');
				return false;
			}

			foreach ($question3_choise as $key => $value)
			{
				$new_key = array_search($value, $answerterm_text);
				if($new_key !== false)
				{
					$question3_choise[$new_key] = $value;
					unset($question3_choise[$key]);
				}
			}
			$question3_choise[0] = T_("Skipped");
		}


		$ready = [];

		$ready[] =
		[
			'id'     => "0.0",
			'name'   => T_("All"),
			'parent' => null,
			'value'  => null,
		];

		foreach ($question1_choise as $key1 => $value1)
		{
			$ready[] =
			[
				'id'     => "1.$key1",
				'name'   => $value1,
				'parent' => '0.0',
				'value'  => null,
			];

			foreach ($question2_choise as $key2 => $value2)
			{
				$ready[] =
				[
					'id'     => "2.$key1.$key2",
					'name'   => $value2,
					'parent' => "1.$key1",
					'value'  => null,
				];

				if($_question3)
				{
					foreach ($question3_choise as $key3 => $value3)
					{
						$ready[] =
						[
							'id'     => "3.$key1.$key2.$key3",
							'name'   => $value3,
							'parent' => "2.$key1.$key2",
							'value'  => null,
						];
					}
				}
			}
		}

		$count_answer = array_sum(array_column($result, 'count'));

		if(!$count_answer)
		{
			$count_answer = 1;
		}

		$ready_key = array_column($ready, 'id');

		foreach ($result as $key => $value)
		{
			if($_question3)
			{
				$check_key = array_search("3.$value[q1].$value[q2].$value[q3]", $ready_key);
			}
			else
			{
				$check_key = array_search("2.$value[q1].$value[q2]", $ready_key);
			}

			if($check_key !== false)
			{
				$ready[$check_key]['value'] = round((intval($value['count']) * 100)/ $count_answer);
				// $ready[$check_key]['value'] = intval($value['count']);
			}
		}

		$ready        = json_encode($ready, JSON_UNESCAPED_UNICODE);

		$table = [];

		foreach ($result as $key => $value)
		{
			$table[] =
			[
				$question1_choise_title => @$question1_choise[$value['q1']],
				$question2_choise_title => @$question2_choise[$value['q2']],
				$question3_choise_title => @$question3_choise[$value['q3']],
				'count'                 => $value['count'],
				'percent'               => round((intval($value['count']) * 100)/ $count_answer, 2),
			];
		}

		$default_args =
		[
			'sort'  => 'count',
			'order' => 'desc',
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default_args, $_args);

		$my_sort        = 'count';
		$my_order       = SORT_DESC;

		if($_args['sort'] && in_array($_args['sort'], ['q1', 'q2', 'q3', 'count']))
		{
			$my_sort = $_args['sort'];
			switch ($my_sort)
			{
				case 'q1':
					$my_sort = $question1_choise_title;
					break;

				case 'q2':
					$my_sort = $question2_choise_title;
					break;

				case 'q3':
					$my_sort = $question3_choise_title;
					break;
			}
		}

		if($_args['order'] && in_array($_args['order'], ['desc', 'asc']))
		{
			$my_order = $_args['order'];
			if($_args['order'] === 'asc')
			{
				$my_order = SORT_ASC;
			}
			else
			{
				$my_order = SORT_DESC;
			}
		}

		$my_sort_detail = $my_sort === 'count' ?  SORT_NUMERIC : SORT_STRING;

		// $result_sort = array_column($table, $my_sort);

		$result_sort = array_column($table, 'count');
		rsort($result_sort);

		$new_table = [];
		foreach ($result_sort as $count)
		{
			foreach ($table as $value)
			{
				if(intval($value['count']) === intval($count))
				{
					$new_table[] = $value;
				}
			}
		}

		// array_multisort($table, $result_sort, $my_order | $my_sort_detail);
		// array_multisort($table, $result_sort, SORT_DESC | SORT_NUMERIC);

		$return             = [];
		$return['chart']    = $ready;
		$return['table']    = $new_table;
		$return['question'] = ['q1' => $question1_choise_title, 'q2' => $question2_choise_title, 'q3' => $question3_choise_title];

		return $return;
	}
}
?>
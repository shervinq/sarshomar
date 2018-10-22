<?php
namespace lib\app\answer;

class chart
{
	public static function advance_chart($_survey_id, $_question1, $_question2, $_question3)
	{
		$query =
		"
			SELECT
				count(myTable.q3) AS `count`,
				myTable.q1,
				myTable.q2,
				myTable.q3
			FROM
			(
				SELECT
					answerdetails.user_id,
				  	MAX(CASE WHEN answerdetails.question_id = $_question1 THEN answerdetails.answerterm_id END) 'q1',
				  	MAX(CASE WHEN answerdetails.question_id = $_question2 THEN answerdetails.answerterm_id END) 'q2',
				  	MAX(CASE WHEN answerdetails.question_id = $_question3 THEN answerdetails.answerterm_id END) 'q3'
				FROM
					answerdetails
				WHERE
					answerdetails.survey_id   = $_survey_id
				GROUP BY
				answerdetails.user_id
			)
			AS `myTable`

			GROUP BY myTable.q1, myTable.q2, myTable.q3

		";

		$result = \dash\db::get($query);

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
		$question1_choise = isset($question1_choise['choice']) ? $question1_choise['choice'] : [];
		$question1_choise = array_column($question1_choise, 'title');
		foreach ($question1_choise as $key => $value)
		{
			$new_key = array_search($value, $answerterm_text);
			if($new_key !== false)
			{
				$question1_choise[$new_key] = $value;
				unset($question1_choise[$key]);
			}
		}

		$question2_choise = \lib\db\questions::get(['id' => $_question2, 'limit' => 1]);
		$question2_choise = \lib\app\question::ready($question2_choise);
		$question2_choise = isset($question2_choise['choice']) ? $question2_choise['choice'] : [];
		$question2_choise = array_column($question2_choise, 'title');
		foreach ($question2_choise as $key => $value)
		{
			$new_key = array_search($value, $answerterm_text);
			if($new_key !== false)
			{
				$question2_choise[$new_key] = $value;
				unset($question2_choise[$key]);
			}
		}

		$question3_choise = \lib\db\questions::get(['id' => $_question3, 'limit' => 1]);
		$question3_choise = \lib\app\question::ready($question3_choise);
		$question3_choise = isset($question3_choise['choice']) ? $question3_choise['choice'] : [];
		$question3_choise = array_column($question3_choise, 'title');
		foreach ($question3_choise as $key => $value)
		{
			$new_key = array_search($value, $answerterm_text);
			if($new_key !== false)
			{
				$question3_choise[$new_key] = $value;
				unset($question3_choise[$key]);
			}
		}


		$ready = [];

		foreach ($question1_choise as $key1 => $value1)
		{
			$ready[] =
			[
				'id'     => "0.$key1",
				'name'   => $value1,
				'parent' => null,
				'value'  => null,
			];

			foreach ($question2_choise as $key2 => $value2)
			{
				$ready[] =
				[
					'id'     => "1.$key1.$key2",
					'name'   => $value2,
					'parent' => "0.$key1",
					'value'  => null,
				];

				foreach ($question3_choise as $key3 => $value3)
				{
					$ready[] =
					[
						'id'     => "2.$key1.$key2.$key3",
						'name'   => $value3,
						'parent' => "1.$key1.$key2",
						'value'  => null,
					];
				}
			}
		}

		$ready_key = array_column($ready, 'id');

		foreach ($result as $key => $value)
		{
			$check_key = array_search("2.$value[q1].$value[q2].$value[q3]", $ready_key);
			if($check_key !== false)
			{
				$ready[$check_key]['value'] = intval($value['count']);
			}

		}

		$ready = json_encode($ready, JSON_UNESCAPED_UNICODE);
		return $ready;
	}
}
?>
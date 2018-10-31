<?php
namespace content_a\report\allquestion;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('list');
		\dash\data::page_title(T_("Question list"));
		\dash\data::page_desc(T_("List of your survey answers by select one question"));

		$dataTable = '[]';

		if(\dash\request::get('id'))
		{

			\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to report dashboard'));

			\content_a\survey\view::load_survey();

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());

			if(!\dash\request::get('questionid'))
			{
				$id = \dash\request::get('id');

				$questionList = \lib\app\question::block_survey($id);

				\dash\data::questionList($questionList);

				self::show_all($questionList);
			}

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}

		\dash\data::dataTable($dataTable);
	}


	private static function show_all($_all)
	{
		$result = [];

		foreach ($_all as $key => $value)
		{

			$id = $value['id'];

			$question_detail = \lib\app\question::get($id);

			$result[$key]['question_detail'] = $question_detail;

			$questionid = $value['id'];

			if(isset($question_detail['type_detail']['chart']))
			{
				$sort  = null;
				$order = null;

				if(\dash\request::get('sort'))
				{
					if(\dash\request::get('sort') === 'answer')
					{
						$sort = 'answerdetails.answerterm_id';
					}
					elseif(in_array(\dash\request::get('sort'), ['value', 'value_all', 'percent']))
					{
						$sort = 'count';
					}
				}

				if(\dash\request::get('order') && in_array(\dash\request::get('order'), ['asc', 'desc']))
				{
					$order = \dash\request::get('order');
				}

				$dataTable = \lib\app\answer::get_result(\dash\request::get('id'), $questionid, $sort, $order);

				$result[$key]['dataTable'] = $dataTable;
				$table = $dataTable;
				if(is_array($table))
				{
					$table = array_map('json_decode', $table);
					$result[$key]['tableRow'] = $table;
					\dash\data::sortLink(\dash\app\sort::make_sortLink(['answer', 'value', 'value_all', 'percent'], \dash\url::this(). '/allquestion'));
				}
				$result[$key]['showChart'] = true;
			}
			else
			{
				$args                              = [];
				$args['pagenation'] = false;
				$args['answerdetails.question_id'] = \dash\coding::decode($questionid);

				$dataTable = \lib\app\answerdetail::list(null, $args);
				$result[$key]['dataTable'] = $dataTable;

				$result[$key]['showChart'] = false;
			}
		}


		\dash\data::allData($result);
	}
}
?>

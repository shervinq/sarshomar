<?php
namespace content_a\report\question;


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
				\dash\redirect::to(\dash\url::this(). '/questionlist?id='. \dash\request::get('id'));
			}
			else
			{
				\dash\data::badge_link(\dash\url::this(). '/questionlist?id='. \dash\request::get('id'));
				\dash\data::badge_text(T_('Back to question list'));

				$question_detail = \content_a\question\view::load_question();

				\dash\data::questionDetail($question_detail);

				$questionid = \dash\request::get('questionid');

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
						elseif(in_array(\dash\request::get('sort'), ['value', 'value_all']))
						{
							$sort = 'count';
						}
					}

					if(\dash\request::get('order') && in_array(\dash\request::get('order'), ['asc', 'desc']))
					{
						$order = \dash\request::get('order');
					}


					$dataTable = \lib\app\answer::get_result(\dash\request::get('id'), $questionid, $sort, $order);

					$table = $dataTable;
					if(is_array($table))
					{
						$table = array_map('json_decode', $table);
						\dash\data::tableRow($table);
						\dash\data::sortLink(\dash\app\sort::make_sortLink(['answer', 'value', 'value_all'], \dash\url::this(). '/question'));
					}
					\dash\data::showChart(true);
				}
				else
				{
					$args                              = [];
					$args['answerdetails.question_id'] = \dash\coding::decode($questionid);

					$dataTable = \lib\app\answerdetail::list(null, $args);
					\dash\data::showChart(false);
				}
			}

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}

		\dash\data::dataTable($dataTable);
	}
}
?>

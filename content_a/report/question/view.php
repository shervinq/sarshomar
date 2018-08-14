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
				$id = \dash\request::get('id');

				$questionList = \lib\app\question::block_survey($id);

				\dash\data::questionList($questionList);
			}
			else
			{
				\dash\data::badge_link(\dash\url::this(). '/question?id='. \dash\request::get('id'));
				\dash\data::badge_text(T_('Back to question list'));

				$question_detail = \content_a\question\view::load_question();

				$questionid = \dash\request::get('questionid');

				if(isset($question_detail['type_detail']['chart']))
				{
					$dataTable = \lib\app\answer::get_result(\dash\request::get('id'), $questionid);
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

			\dash\data::include_chart(true);
		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}

		\dash\data::dataTable($dataTable);
	}
}
?>

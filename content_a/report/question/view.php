<?php
namespace content_a\report\question;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('list');
		\dash\data::page_title(T_("Question list"));
		\dash\data::page_desc(T_("List of your survey answers by select one question"));

		if(\dash\request::get('id'))
		{

			\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to report dashboard'));

			\content_a\survey\view::load_survey();

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());

			if(!\dash\request::get('questionid'))
			{
				$id = \dash\request::get('id');

				$dataTable = \lib\app\question::block_survey($id);

				\dash\data::dataTable($dataTable);
			}
			else
			{
				$question_detail = \content_a\question\view::load_question();

				$questionid = \dash\request::get('questionid');

				$result = \lib\app\answer::get_result(\dash\request::get('id'), $questionid);
				\dash\data::chartData($result);
			}

			\dash\data::include_chart(true);
		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

<?php
namespace content_a\report\questionlist;


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

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}

		\dash\data::dataTable($dataTable);
	}
}
?>

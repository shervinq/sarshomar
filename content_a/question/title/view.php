<?php
namespace content_a\question\title;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\content_a\survey\view::load_survey();
		\dash\data::page_title(T_("Edit question"). ' | '. \dash\data::surveyRow_title());
		\content_a\question\view::load_question();

		\dash\data::page_desc(T_("You can edit your question detail"));

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to question dashboard'));

	}
}
?>

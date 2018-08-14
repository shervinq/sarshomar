<?php
namespace content_a\survey\welcome;


class view
{
	public static function config()
	{

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Welcome message"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("You can set specail welcome message on your survey."));
		\dash\data::page_pictogram('hourglass-start');

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));
		\dash\data::include_editor(true);

	}
}
?>

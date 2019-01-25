<?php
namespace content_a\survey\duplicate;


class view
{
	public static function config()
	{
		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Duplicate survey"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("You can make a duplicate survey from this survey."));
		\dash\data::page_pictogram('publish');

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));
	}
}
?>

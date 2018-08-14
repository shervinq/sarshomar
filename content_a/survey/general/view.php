<?php
namespace content_a\survey\general;


class view
{
	public static function config()
	{

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("General Settings"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("You can edit your survey general settings."));
		\dash\data::page_pictogram('cog');


		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));
		\dash\data::include_editor(true);

	}
}
?>

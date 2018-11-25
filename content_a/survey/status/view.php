<?php
namespace content_a\survey\status;


class view
{
	public static function config()
	{

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Change status"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("You can edit your survey status."));
		\dash\data::page_pictogram('publish');


		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));

	}
}
?>

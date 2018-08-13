<?php
namespace content_a\survey\privacy;


class view
{
	public static function config()
	{

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Survey privacy"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("Manage your privacy."));
		\dash\data::page_pictogram('lock');

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));

	}
}
?>

<?php
namespace content_a\survey\branding;


class view
{
	public static function config()
	{

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Branding"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("Be a different and classy!"));
		\dash\data::page_pictogram('medal');

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));

	}
}
?>

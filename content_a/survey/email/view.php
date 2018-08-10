<?php
namespace content_a\survey\email;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Edit survey"). ' | '. \dash\data::surveyRow_title());

		\dash\data::page_desc(T_("You can edit your survey detail"));

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));

	}
}
?>

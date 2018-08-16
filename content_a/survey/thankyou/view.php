<?php
namespace content_a\survey\thankyou;


class view
{
	public static function config()
	{

		\content_a\survey\view::load_survey();

		\dash\data::page_title(T_("Thank you message"). ' | '. \dash\data::surveyRow_title());
		\dash\data::page_desc(T_("Say thank you to your survey participants."));
		\dash\data::page_pictogram('flag-checkered');

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to survey dashboard'));


	}
}
?>

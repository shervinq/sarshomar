<?php
namespace content_a\report\wordcloud;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('dzone');
		\dash\data::page_title(T_("Word cloud"));
		\dash\data::page_desc(T_("List of your survey word"));

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to report dashboard'));

		\dash\data::allWordCloud(\lib\app\survey::word_cloud(\dash\request::get('id')));

	}
}
?>

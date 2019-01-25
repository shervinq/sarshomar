<?php
namespace content_a\report\wordcloud;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('dzone');
		\dash\data::page_title(T_("Word cloud"));
		\dash\data::page_desc(T_("List of your survey word"));

		\dash\data::allWordCloud(\lib\app\survey::word_cloud(\dash\request::get('id')));

	}
}
?>

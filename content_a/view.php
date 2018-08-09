<?php
namespace content_a;

class view
{
	public static function config()
	{

		\dash\data::site_title(T_("Sarshomar"));
		\dash\data::site_desc(T_("Focus on your question. Do not be too concerned about how to ask or analyze."));
		\dash\data::site_slogan(T_("Ask Anyone Anywhere"));
		\dash\data::page_desc(\dash\data::site_desc(). ' | '. \dash\data::site_slogan());

		// for pushstate of main page
		\dash\data::template_xhr('content/main/layout-xhr.html');
		\dash\data::display_poll('content_a/poll/layout.html');
		\dash\data::display_question('content_a/question/layout.html');

		\dash\data::template_social('content/template/social.html');
		\dash\data::template_share('content/template/share.html');
		\dash\data::include_adminPanel(true);
		\dash\data::include_css(false);
		\dash\data::include_js(false);
		\dash\data::bodyclass('fixed unselectable');
	}
}
?>
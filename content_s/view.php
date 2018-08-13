<?php
namespace content_s;

class view
{
	public static function config()
	{

		\dash\data::site_title(T_("Sarshomar"));
		\dash\data::site_desc(T_("Focus on your question. Do not be too concerned about how to ask or analyze."));
		\dash\data::site_slogan(T_("Ask Anyone Anywhere"));
		\dash\data::page_desc(\dash\data::site_desc(). ' | '. \dash\data::site_slogan());


		\dash\data::include_adminPanel(true);
		\dash\data::include_css(false);
		\dash\data::include_js(false);


		\dash\data::service_title(T_("Sarshomar"));
		\dash\data::service_desc(T_("Focus on your question. Do not be too concerned about how to ask or analyze."));
		\dash\data::service_slogan(T_("Ask Anyone Anywhere"));
		\dash\data::service_logo(\dash\url::site(). '/static/siftal/images/logo/sarshomar.png');
		\dash\data::service_url('https://sarshomar.com');

	}
}
?>
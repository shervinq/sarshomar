<?php
namespace content;

class view
{
	public static function config()
	{
		// define default value for global
		\dash\data::site_title(T_("Sarshomar"));
		\dash\data::site_desc(T_("Focus on your question. Do not be too concerned about how to ask or analyze."));
		\dash\data::site_slogan(T_("Ask Anyone Anywhere"));

		\dash\data::page_desc(\dash\data::site_desc(). ' '. T_("Equipped with an integrated platform, Sarshomar has made it possible for you to ask your questions via any means."));

		\dash\data::bodyclass('unselectable');


		if(\dash\permission::supervisor())
		{
			$questionAnsweredRow = \lib\app\report\question_answered::get(true);
			\dash\data::questionAnsweredRow($questionAnsweredRow);
		}

		$questionAnswered = \lib\app\report\question_answered::get();
		\dash\data::questionAnswered($questionAnswered);
	}
}
?>
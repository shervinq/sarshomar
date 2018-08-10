<?php
namespace content_a\question\choise;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('edit');

		\content_a\poll\view::load_poll();
		\content_a\question\view::load_question();

		\dash\data::page_title(T_("Edit question"). ' | '. \dash\data::pollRow_title());

		\dash\data::page_desc(T_("You can edit your question detail"));

		\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
		\dash\data::badge_text(T_('Back to question dashboard'));

		if(!\dash\data::haveChoise())
		{
			\dash\redirect::to(\dash\url::this(). '/general?'. http_build_query(\dash\request::get()));
		}

	}
}
?>

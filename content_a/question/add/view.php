<?php
namespace content_a\question\add;


class view
{
	public static function config()
	{
		if(!\dash\request::get('id'))
		{
			\dash\redirect::to(\dash\url::here());
		}

		\dash\data::page_pictogram('plus');

		\dash\data::page_title(T_("Add new question"));
		\dash\data::page_desc(T_("add new question by some data and can edit it later"));

		if(\dash\request::get('new'))
		{
			\dash\data::badge_link(\dash\url::here(). '/poll?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to poll dashboard'));
		}
		else
		{
			\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to question list'));
		}

		$allType =
		[
			['key' => 'short_text', 'title' => 'short_text'],
			['key' => 'long_text', 'title' => 'long_text'],
			['key' => 'single_choise', 'title' => 'single_choise'],
			['key' => 'mutli_choise', 'title' => 'mutli_choise'],
			['key' => 'picture_choice', 'title' => 'picture_choice'],
			['key' => 'yes_no', 'title' => 'yes/no'],
			['key' => 'legal', 'title' => 'legal'],
			['key' => 'email', 'title' => 'email'],
			['key' => 'scale', 'title' => 'scale'],
			['key' => 'rating', 'title' => 'rating'],
			['key' => 'date', 'title' => 'date'],
			['key' => 'number', 'title' => 'number'],
			['key' => 'dropdown', 'title' => 'dropdown'],
			['key' => 'fileid', 'title' => 'fileid'],
			['key' => 'website', 'title' => 'website'],

		];

		\dash\data::allType($allType);
	}
}
?>

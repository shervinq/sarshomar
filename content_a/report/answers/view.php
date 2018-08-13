<?php
namespace content_a\report\answers;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('list');
		\dash\data::page_title(T_("Answers"));
		\dash\data::page_desc(T_("List of your survey answers"));

		if(\dash\request::get('id'))
		{
			\dash\data::badge_link(\dash\url::this(). '?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to report dashboard'));

			$id        = \dash\request::get('id');
			$load_survey = \lib\app\survey::get($id);
			if(!$load_survey)
			{
				\dash\header::status(404, T_("Invalid survey id"));
			}
			\dash\data::dataRow($load_survey);

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::dataRow_title());

			if(\dash\request::get('status'))
			{
				$args['status'] = \dash\request::get('status');
			}

			$args =
			[
				'sort'  => \dash\request::get('sort'),
				'order' => \dash\request::get('order'),
			];


			$args['survey_id'] = \dash\coding::decode(\dash\request::get('id'));

			if(\dash\request::get('type'))
			{
				$args['type'] = \dash\request::get('type');
			}

			if(!$args['order'])
			{
				$args['order'] = 'DESC';
			}

			if(!$args['sort'])
			{
				$args['sort'] = 'id';
			}

			$search_string = \dash\request::get('q');

			\dash\data::sortLink(\content_cp\view::make_sort_link(\lib\app\answer::$sort_field, \dash\url::this()));
			$dataTable = \lib\app\answer::list($search_string, $args);

			\dash\data::dataTable($dataTable);

			$filterArray = $args;
			unset($filterArray['survey_id']);


			// set dataFilter
			$dataFilter = \dash\app\sort::createFilterMsg($search_string, $filterArray);
			\dash\data::dataFilter($dataFilter);

		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}
	}
}
?>

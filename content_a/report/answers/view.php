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

			\dash\data::badge2_link(\dash\url::that(). '?id='. \dash\request::get('id'). '&export=true');
			\dash\data::badge2_text(T_('Export CSV'));

			\content_a\survey\view::load_survey();

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());

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


			if(\dash\request::get('export') === 'true')
			{
				\lib\app\answer::export_all(\dash\request::get('id'));
				return;
			}

			$search_string = \dash\request::get('q');

			\dash\data::sortLink(\dash\app\sort::make_sortLink(\lib\app\answer::$sort_field, \dash\url::this(). '/answers'));
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

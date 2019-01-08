<?php
namespace content_a\alllist;


class view
{
	public static function config()
	{
		if(!\dash\permission::supervisor())
		{
			\dash\header::status(403);
		}

		$filterArgs = [];

		\dash\data::page_title(T_("Questionnaires"));
		\dash\data::page_desc(T_("Manage all of your surveys and easily add new one or manage exisiting."));
		\dash\data::page_pictogram('tachometer');

		$arg                 =
		[
			'order' => \dash\request::get('order'),
			'sort' => \dash\request::get('sort'),
		];
		$arg['limit']        = 25;
		$arg['join_creator'] = true;

		if(\dash\request::get('status'))
		{
			$arg['surveys.status'] = \dash\request::get('status');
			$filterArgs['status'] = \dash\request::get('status');
		}

		$user_id = null;
		if(\dash\request::get('user_id'))
		{
			$user_id = \dash\coding::decode(\dash\request::get('user_id'));
			if($user_id)
			{
				$arg['surveys.user_id'] = $user_id;
			}
		}

		$q                   = \dash\request::get('q');
		$dataTable           = \lib\app\survey::list($q, $arg);

		$sortLink = \dash\app\sort::make_sortLink(\lib\app\survey::$sort_field, \dash\url::this());

		if(!is_array($dataTable))
		{
			$dataTable = [];
		}

		\dash\data::sortLink($sortLink);
		\dash\data::dataTable($dataTable);

		if($user_id )
		{
			if(isset($dataTable[0]['user_displayname']))
			{
				$filterArgs[T_("Creator")] = $dataTable[0]['user_displayname'];
			}

		}

		$allStatus = \lib\db\surveys::get_all_status();
		\dash\data::allStatus($allStatus);

		// set dataFilter
		$dataFilter = \dash\app\sort::createFilterMsg(\dash\request::get('q'), $filterArgs);
		\dash\data::dataFilter($dataFilter);
	}
}
?>
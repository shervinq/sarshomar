<?php
namespace content_a\report\home;


class view
{
	public static function config()
	{
		\dash\data::page_pictogram('chart');
		\dash\data::page_title(T_("Report list"));
		\dash\data::page_desc(T_("Check your survey report"));

		if(\dash\request::get('id'))
		{
			\dash\data::badge_link(\dash\url::here(). '/survey?id='. \dash\request::get('id'));
			\dash\data::badge_text(T_('Back to survey dashboard'));

			\content_a\survey\view::load_survey();

			\dash\data::page_title(\dash\data::page_title(). ' | '. \dash\data::surveyRow_title());
		}
		else
		{
			\dash\redirect::to(\dash\url::here());
		}

		$visitor_page = \dash\db\visitors::visitor_page(\dash\data::surveyRow_s_url());
		$survey_id = \dash\coding::decode(\dash\request::get('id'));
		$count_start = 0;
		$count_complete = 0;
		if($survey_id)
		{
			$count_start = \lib\db\answers::get_count(['survey_id' => $survey_id]);
			$count_complete = \lib\db\answers::get_count(['survey_id' => $survey_id, 'complete' => 1]);
		}

		$qifChart =
		[
	        ['Website visits', intval($visitor_page)],
	        ['Start', intval($count_start)],
	        ['Complete', intval($count_complete)]
  		];

		\dash\data::qifChart(json_encode($qifChart, JSON_UNESCAPED_UNICODE));

	}
}
?>

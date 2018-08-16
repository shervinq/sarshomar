<?php
namespace content_a\question;


class view
{
	public static function load_question()
	{
		\content_a\survey\view::load_survey();

		\dash\data::badge2_link(\dash\url::here(). '/survey?id='. \dash\request::get('id'));
		\dash\data::badge2_text(T_('Back to survey dashboard'));

		$load = null;
		$id = \dash\request::get('questionid');
		if($id)
		{
			$load = \lib\app\question::get($id);
			if(!$load)
			{
				\dash\header::status(404, T_("Invalid question id"));
			}

			if(isset($load['type']))
			{
				\dash\data::choiceDetail(\lib\app\question::get_type($load['type']));
			}

			\dash\data::dataRow($load);

			return $load;
		}
		else
		{
			if(!$load)
			{
				if(\dash\request::get('type'))
				{
					$dataRow = [];
					$dataRow['type'] = \dash\request::get('type');

					if(!\lib\app\question::get_type(\dash\request::get('type')))
					{
						\dash\header::status(404, T_("Invalid type"));
					}

					$dataRow['type_detail'] = \lib\app\question::get_type($dataRow['type']);
					$dataRow['setting'] = $dataRow['type_detail'];
					\dash\data::choiceDetail(\lib\app\question::get_type($dataRow['type']));
					\dash\data::dataRow($dataRow);
				}
				else
				{
					\dash\redirect::to(\dash\url::this().'?id='. \dash\request::get('id'));
				}
			}
		}
	}
}
?>

<?php
namespace content_a\question;


class view
{
	public static function load_question()
	{
		\content_a\survey\view::load_survey();

		$id = \dash\request::get('questionid');
		$load = \lib\app\question::get($id);
		if(!$load)
		{
			\dash\header::status(404, T_("Invalid question id"));
		}

		if(isset($load['type']))
		{
			\dash\data::haveChoise(\lib\app\question::get_type($load['type'], 'choice'));
			\dash\data::haveRandom(\lib\app\question::get_type($load['type'], 'random'));
			\dash\data::haveOtherChoise(\lib\app\question::get_type($load['type'], 'otherchoice'));
			\dash\data::haveMaxchar(\lib\app\question::get_type($load['type'], 'maxchar'));
			\dash\data::haveUploadChoise(\lib\app\question::get_type($load['type'], 'upload_choice'));
		}

		\dash\data::dataRow($load);

		return $load;
	}
}
?>

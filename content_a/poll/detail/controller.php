<?php
namespace content_a\festival\detail;


class controller
{
	public static function routing()
	{
		\dash\permission::access('fpFestivalAdd');

		\content_a\festival\controller::check_festival_id();

	}
}
?>

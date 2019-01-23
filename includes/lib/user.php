<?php
namespace lib;

class user
{

	public static function user_in_all_table($_user_id)
	{

		$result                  = [];

		$result['answerdetails'] =
		[
			'count'  => \lib\db\answerdetails::get_count(['user_id' => $_user_id]),
			'link'   => null,
			'encode' => false,
		];

		$result['answers']       =
		[
			'count'  => \lib\db\answers::get_count(['user_id' => $_user_id]),
			'link'   => null,
			'encode' => false,
		];

		$result['surveys']       =
		[
			'count'  => \lib\db\surveys::get_count(['user_id' => $_user_id]),
			'link'   => null,
			'encode' => false,
		];

		return $result;
	}
}
?>
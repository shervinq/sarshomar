<?php
namespace lib;

class user
{

	public static function user_in_all_table($_user_id)
	{

		$result                  = [];
		$result['answerdetails'] = \lib\db\answerdetails::get_count(['user_id' => $_user_id]);
		$result['answers']       = \lib\db\answers::get_count(['user_id' => $_user_id]);
		// $result['answerterms']   = \lib\db\answerterms::get_count(['user_id' => $_user_id]);
		// $result['questions']     = \lib\db\questions::get_count(['user_id' => $_user_id]);
		$result['surveys']       = \lib\db\surveys::get_count(['user_id' => $_user_id]);

		return $result;
	}
}
?>
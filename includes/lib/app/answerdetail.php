<?php
namespace lib\app;

/**
 * Class for answer.
 */
class answerdetail
{
	public static $sort_field =
	[
		'title',
		'status',
		'signup',
	];


	/**
	 * Gets the survey.
	 *
	 * @param      <type>  $_args  The arguments
	 *
	 * @return     <type>  The survey.
	 */
	public static function list($_string = null, $_args = [])
	{
		if(!\dash\user::id())
		{
			return false;
		}

		$default_meta =
		[
			'sort'  => null,
			'order' => null,
		];

		if(!is_array($_args))
		{
			$_args = [];
		}

		$_args = array_merge($default_meta, $_args);

		if($_args['sort'] && !in_array($_args['sort'], self::$sort_field))
		{
			$_args['sort'] = null;
		}


		$result            = \lib\db\answerdetails::search($_string, $_args);
		$temp              = [];

		foreach ($result as $key => $value)
		{
			$check = self::ready($value);
			if($check)
			{
				$temp[] = $check;
			}
		}

		return $temp;
	}


	/**
	 * ready data of question to load in api
	 *
	 * @param      <type>  $_data  The data
	 */
	public static function ready($_data)
	{
		$result = [];
		foreach ($_data as $key => $value)
		{

			switch ($key)
			{


				case 'id':
				case 'user_id':
					$result[$key] = \dash\coding::encode($value);
					break;


				default:
					$result[$key] = $value;
					break;
			}
		}

		return $result;
	}
}
?>
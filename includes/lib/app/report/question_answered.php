<?php
namespace lib\app\report;


class question_answered
{

	public static function get($_raw = false)
	{
		$now = self::count_question_detail();

		// 1746670 -- old answerd
		$old = 1746670;
		$sum = intval($now) + $old;

		if($_raw)
		{
			$result =
			[
				'old' => $old,
				'now' => $now,
				'sum' => $sum,
			];

			return $result;
		}
		else
		{
			return $sum;
		}

	}


	private static function count_question_detail()
	{
		$load_file = self::load_file();

		if(isset($load_file['count']))
		{
			return intval($load_file['count']);
		}

		// never!
		return 0;
	}


	private static function load_file()
	{
		$refresh_time = 60 * 10;

		$dir = __DIR__. '/question_answered.me.json';

		$date = date("Y-m-d H:i:s");

		if(!is_file($dir))
		{
			$array = self::update_file($dir, $date);
			return $array;
		}
		else
		{
			$load = \dash\file::read($dir);

			if(is_string($load))
			{
				$load = json_decode($load, true);

				if(is_array($load))
				{
					if(isset($load['date']))
					{
						if(time() - strtotime($load['date']) > $refresh_time)
						{
							$array = self::update_file($dir, $date);
							return $array;
						}
						else
						{
							return $load;
						}
					}
					else
					{
						$array = self::update_file($dir, $date);
						return $array;
					}
				}
				else
				{
					$array = self::update_file($dir, $date);
					return $array;
				}
			}
		}
	}


	private static function update_file($_addr, $_date)
	{
		$count = self::db_count();
		$array = $json =
		[
			'count' => $count,
			'date' => $_date,
		];

		$json = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		\dash\file::write($_addr, $json);
		return $array;
	}


	private static function db_count()
	{
		$result = \lib\db\answerdetails::get_count();
		return intval($result);
	}
}
?>

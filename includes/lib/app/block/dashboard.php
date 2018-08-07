<?php
namespace lib\app\block;

trait dashboard
{
	public static $life_time = 60 * 1;

	public static function dashboard($_clean_cache = false)
	{
		$result                        = [];
		// $result['count_all']           = self::get_count_block(['academy_id' => \lib\academy::query_id()], $_clean_cache);
		// $result['count_enable']        = self::get_count_block(['academy_id' => \lib\academy::query_id(), 'status' => 'enable'], $_clean_cache);

		return $result;
	}


	// private static function get_count_block($_where = null, $_clean_cache = false)
	// {
	// 	$key = "count_block_". json_encode($_where). '_'. \lib\academy::id();

	// 	if($_clean_cache)
	// 	{
	// 		\dash\session::set($key, null);
	// 		return null;
	// 	}

	// 	$result = \dash\session::get($key);
	// 	if($result === null)
	// 	{
	// 		$result = \lib\db\blocks::get_count($_where);
	// 		$result = intval($result);
	// 		\dash\session::set($key, $result, null, self::$life_time);
	// 	}

	// 	return $result;
	// }




}
?>
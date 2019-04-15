<?php
namespace lib\app;

/**
 * Class for survey.
 */
class survey
{

	use survey\add;
	use survey\edit;
	use survey\datalist;
	use survey\dashboard;


	public static $raw_field =
	[
		'redirect',
		'brandingmeta',
		'welcomemedia',
		'thankyoumedia',
		'desc',
		'brandingdesc',
		'emailmsg',
		'welcomedesc',
		'thankyoudesc',
	];

	public static function word_cloud($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			return false;
		}

		$word         = [];
		$word_survery = \lib\db\surveys::get(['id' => $id, 'limit' => 1]);

		if(is_array($word_survery))
		{
			foreach ($word_survery as $key => $value)
			{
				if(in_array($key, ['title', 'brandingtitle', 'trans', 'welcometitle', 'welcomedesc', 'thankyoudesc', 'thankyoutitle']))
				{
					$word[] = self::remove_2_char($value);
				}
			}
		}

		$load_question = \lib\db\questions::get(['survey_id' => $id]);
		if(is_array($load_question))
		{
			foreach ($load_question as $key => $value)
			{
				if(is_array($value))
				{
					foreach ($value as $k => $v)
					{
						if(in_array($k, ['title', 'desc']))
						{
							$word[] = self::remove_2_char($v);
						}
					}
				}
			}
		}

		$word = implode(' ', $word);
		return $word;

	}

	private static function remove_2_char($_text)
	{
		$word = [];
		$_text = strip_tags($_text);
		$_text = str_replace('[', ' ', $_text);
		$_text = str_replace(']', ' ', $_text);
		$_text = str_replace('{', ' ', $_text);
		$_text = str_replace('}', ' ', $_text);
		$_text = str_replace('"', ' ', $_text);
		$_text = str_replace('؛', ' ', $_text);
		$_text = str_replace("'", ' ', $_text);
		$_text = str_replace('(', ' ', $_text);
		$_text = str_replace(')', ' ', $_text);
		$_text = str_replace(':', ' ', $_text);
		$_text = str_replace(',', ' ', $_text);
		$_text = str_replace('،', ' ', $_text);
		$_text = str_replace('-', ' ', $_text);
		$_text = str_replace('_', ' ', $_text);
		$_text = str_replace('?', ' ', $_text);
		$_text = str_replace('؟', ' ', $_text);
		$_text = str_replace('.', ' ', $_text);
		$_text = str_replace('=', ' ', $_text);
		$_text = str_replace('
', ' ', $_text);

		$_text = str_replace("\n", ' ', $_text);
		$_text = str_replace('!', ' ', $_text);
		$_text = str_replace('&nbsp;', ' ', $_text);

		$split = explode(" ", $_text);

		foreach ($split as $key => $value)
		{
			$value = trim($value);
			if(mb_strlen($value) > 2 && !is_numeric($value))
			{
				$word[] = $value;
			}
		}

		$word = implode(' ', $word);
		$word = trim($word);
		return $word;
	}


	public static function fire($_id, $_site = false)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			if($_site)
			{
				\dash\redirect::to(\dash\url::kingdom());
				return false;
			}
			else
			{
				return false;
			}
		}

		$load = \lib\app\survey::get($_id);


		if(!$load || !isset($load['status']) || !isset($load['privacy']) || !isset($load['user_id']))
		{
			if($_site)
			{
				\dash\header::status(404, T_("Survay not found"));
			}
			return false;
		}

		if(isset($load['lang']))
		{
			if($load['lang'] !== \dash\language::current())
			{
				$new_url = \dash\url::site();
				$new_url .= '/'. $load['lang']. '/s/'. $_id;
				if(\dash\url::child())
				{
					$new_url .= '/'. \dash\url::child();
				}

				if(\dash\request::get())
				{
					$new_url .= '?'. \dash\url::query();
				}

				if($_site)
				{
					\dash\redirect::to($new_url);
					return false;
				}
			}
		}

		if(intval(\dash\coding::decode($load['user_id'])) === intval(\dash\user::id()))
		{
			\dash\data::mySurvey(true);
		}

		if(isset($load['starttime']) || isset($load['endtime']))
		{
			$starttime = $load['starttime'];
			$endtime   = $load['endtime'];

			if($starttime)
			{
				$starttime = strtotime($starttime);
				if($starttime > time())
				{
					\dash\temp::set('survey_error', 'time');
					\dash\temp::set('survey_error_desc', T_("The survey will be available on :val", ['val' => \dash\datetime::fit(date("Y-m-d H:i", $starttime))]));
					if(self::isReturnFalse())
					{
						return false;
					}
				}
			}

			if($endtime)
			{
				$endtime = strtotime($endtime);
				if($endtime < time())
				{
					\dash\temp::set('survey_error', 'time');
					\dash\temp::set('survey_error_desc', T_("This survey has been available by :val", ['val' => \dash\datetime::fit(date("Y-m-d H:i", $endtime))]));
					if(self::isReturnFalse())
					{
						return false;
					}
				}
			}
		}


		// check user id and privacy and password
		if($load['status'] !== 'publish')
		{
			if(self::isReturnFalse())
			{
				if($_site)
				{
					\dash\temp::set('survey_error', 'status');
					// \dash\header::status(403, T_("This survey is not publish"));
				}
				// @check
				// in tg must be make a msg for show in user This survey is not publish
				// @javad
				return false;
			}
		}



		\dash\data::surveyRow($load);
		return $load;
	}


	// in some mode need to retrun false and someone needless to retrun false
	private static function isReturnFalse()
	{
		if(!\dash\permission::supervisor())
		{
			if(!\dash\data::mySurvey())
			{
				return true;
			}
		}
		return false;
	}

	public static function get($_id)
	{
		// if(!\dash\user::id())
		// {
		// 	return false;
		// }

		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Survay id not set"));
			return false;
		}


		$get = \lib\db\surveys::get(['id' => $id, 'limit' => 1]);

		if(!$get)
		{
			\dash\notif::error(T_("Invalid survey id"));
			return false;
		}

		// if(intval($get['user_id']) !== intval(\dash\user::id()))
		// {
		// 	if(!\dash\permission::supervisor())
		// 	{
		// 		return false;
		// 	}
		// }

		$result = self::ready($get);

		return $result;
	}


	/**
	 * check args
	 *
	 * @return     array|boolean  ( description_of_the_return_value )
	 */
	public static function check($_id = null, $_load = [])
	{

		$title = \dash\app::request('title');
		if(\dash\app::isset_request('title') && !$title)
		{
			\dash\notif::error(T_("Please fill the survey title"), 'title');
			return false;
		}

		if(mb_strlen($title) >= 500)
		{
			\dash\notif::error(T_("Please fill the survey title less than 500 character"), 'title');
			return false;
		}

		$language = \dash\app::request('language');
		if($language && mb_strlen($language) !== 2)
		{
			\dash\notif::error(T_("Invalid parameter language"), 'language');
			return false;
		}

		if($language && !\dash\language::check($language))
		{
			\dash\notif::error(T_("Invalid parameter language"), 'language');
			return false;
		}

		$password = \dash\app::request('password');
		if($password && mb_strlen($password) >= 200)
		{
			\dash\notif::error(T_("Please fill the survey password less than 200 character"), 'password');
			return false;
		}

		$privacy = \dash\app::request('privacy');
		if($privacy && !in_array($privacy, ['public', 'private']))
		{
			\dash\notif::error(T_("Invalid privacy of survey"), 'privacy');
			return false;
		}

		$status = \dash\app::request('status');
		if($status && !in_array($status, ['draft','publish','expire','deleted','lock','awaiting','question','filter','close', 'full']))
		{
			\dash\notif::error(T_("Invalid status of survey"), 'status');
			return false;
		}

		if(\dash\app::isset_request('status') && !$status)
		{
			\dash\notif::error(T_("Invalid status of survey"), 'status');
			return false;
		}

		$branding      = \dash\app::request('branding') ? 1 : null;
		$brandingtitle = \dash\app::request('brandingtitle');
		$brandingdesc  = \dash\app::request('brandingdesc');
		$brandingmeta  = \dash\app::request('brandingmeta');
		if(is_array($brandingmeta))
		{
			$brandingmeta = json_encode($brandingmeta, JSON_UNESCAPED_UNICODE);
		}

		$redirect = \dash\app::request('redirect');
		if($redirect && mb_strlen($redirect) >= 2000)
		{
			\dash\notif::error(T_("Please fill the survey redirect less than 2000 character"), 'redirect');
			return false;
		}

		$progresbar = \dash\app::request('progresbar') ? 1 : null;

		$trans  = \dash\app::request('trans');

		$email  = \dash\app::request('email') ? 1 : null;

		$emailtitle = \dash\app::request('emailtitle');
		if($emailtitle && mb_strlen($emailtitle) >= 500)
		{
			\dash\notif::error(T_("Please fill the survey emailtitle less than 500 character"), 'emailtitle');
			return false;
		}

		$emailto = \dash\app::request('emailto');
		if($emailto && mb_strlen($emailto) >= 500)
		{
			\dash\notif::error(T_("Please fill the survey emailto less than 500 character"), 'emailto');
			return false;
		}

		$emailmsg      = \dash\app::request('emailmsg');

		$welcometitle = \dash\app::request('welcometitle');
		$welcomedesc  = \dash\app::request('welcomedesc');
		$welcomemedia = \dash\app::request('welcomemedia');
		if(is_array($welcomemedia))
		{
			$welcomemedia = json_encode($welcomemedia, JSON_UNESCAPED_UNICODE);
		}

		$thankyoutitle = \dash\app::request('thankyoutitle');
		$thankyoudesc  = \dash\app::request('thankyoudesc');
		$thankyoumedia = \dash\app::request('thankyoumedia');
		if(is_array($thankyoumedia))
		{
			$thankyoumedia = json_encode($thankyoumedia, JSON_UNESCAPED_UNICODE);
		}

		$args    = [];
		$setting = [];

		if(\dash\app::isset_request('buttontitle'))
		{
			$buttontitle                    = \dash\app::request('buttontitle');
			$buttontitle                    = \dash\safe::remove_nl($buttontitle);
			$setting['buttontitle'] = $buttontitle;
		}

		if(\dash\app::isset_request('forcelogin'))
		{
			$setting['forcelogin'] = \dash\app::request('forcelogin') ? true : false;
		}

		if(\dash\app::isset_request('autoredirect'))
		{
			$setting['autoredirect'] = \dash\app::request('autoredirect') ? true : false;
			$redirecttime            = \dash\app::request('redirecttime');

			if($redirecttime && !is_numeric($redirecttime))
			{
				\dash\notif::error(T_("Redirect time must be a number"), 'redirecttime');
				return false;
			}

			if($redirecttime === '')
			{
				$redirecttime = null;
			}
			else
			{
				$redirecttime = intval($redirecttime);
				$redirecttime = abs($redirecttime);
				if($redirecttime > 300)
				{
					\dash\notif::error(T_("Redirect time must be less than 300"), 'redirecttime');
					return false;
				}
			}

			$setting['redirecttime'] = isset($redirecttime) ? $redirecttime : null;
		}





		if(!empty($setting))
		{
			$args['setting'] = json_encode($setting, JSON_UNESCAPED_UNICODE);
		}


		$startdate = \dash\app::request('startdate');
		if($startdate)
		{
			$startdate = \dash\date::db($startdate);

			if($startdate === false)
			{
				\dash\notif::error(T_("Invalid start date"), 'startdate');
			}

			$startdate = \dash\date::force_gregorian($startdate);
			$startdate = \dash\date::db($startdate);
		}

		$enddate = \dash\app::request('enddate');
		if($enddate)
		{
			$enddate = \dash\date::db($enddate);

			if($enddate === false)
			{
				\dash\notif::error(T_("Invalid start date"), 'enddate');
			}

			$enddate = \dash\date::force_gregorian($enddate);
			$enddate = \dash\date::db($enddate);
		}


		$starttime = \dash\app::request('starttime');
		if($starttime)
		{
			$starttime = \dash\date::make_time($starttime);
			if($starttime === false)
			{
				\dash\notif::error(T_("Invalid statrt time"), 'starttime');
				return false;
			}

			if(!$starttime)
			{
				$starttime = date("H:i");
			}
		}


		$endtime = \dash\app::request('endtime');
		if($endtime)
		{
			$endtime = \dash\date::make_time($endtime);
			if($endtime === false)
			{
				\dash\notif::error(T_("Invalid statrt time"), 'endtime');
				return false;
			}

			if(!$endtime)
			{
				$endtime = date("H:i");
			}
		}

		if($startdate && $starttime)
		{
			$startdate = $startdate . ' '. $starttime;
		}

		if($enddate && $endtime)
		{
			$enddate = $enddate . ' '. $endtime;
		}

		if($startdate && $enddate)
		{
			$datetime1 = new \DateTime($startdate);
			$datetime2 = new \DateTime($enddate);

			if($datetime1 >= $datetime2)
			{

				\dash\notif::error(T_("Start date must be less than end date!"), ['element' => ['startdate', 'enddate', 'starttime', 'endtime']]);
				return false;
			}
		}

		$mobiles = self::mobiles($_id, $_load);
		if($mobiles !== false)
		{
			$args['mobiles']       = $mobiles;
		}


		$desc     = \dash\app::request('desc');
		$fav = \dash\app::request('fav') ? 1 : null;

		$args['desc']          = $desc;
		$args['title']         = $title;
		$args['lang']          = $language;
		$args['password']      = $password;
		$args['privacy']       = $privacy;
		$args['status']        = $status;
		$args['branding']      = $branding;
		$args['brandingtitle'] = $brandingtitle;
		$args['brandingdesc']  = $brandingdesc;
		$args['brandingmeta']  = $brandingmeta;
		$args['redirect']      = $redirect;
		$args['progresbar']    = $progresbar;
		$args['trans']         = $trans;
		$args['email']         = $email;
		$args['emailtitle']    = $emailtitle;
		$args['emailto']       = $emailto;
		$args['emailmsg']      = $emailmsg;
		$args['welcometitle']  = $welcometitle;
		$args['welcomedesc']   = $welcomedesc;
		$args['welcomemedia']  = $welcomemedia;
		$args['thankyoutitle'] = $thankyoutitle;
		$args['thankyoudesc']  = $thankyoudesc;
		$args['thankyoumedia'] = $thankyoumedia;
		$args['fav']      = $fav;
		$args['starttime']     = $startdate;
		$args['endtime']       = $enddate;

		return $args;
	}


	private static function mobiles($_id, $_load)
	{
		$new = \dash\app::request('mobiles');
		$old = null;
		if(isset($_load['mobiles']))
		{
			$old = $_load['mobiles'];
		}

		$new = self::filterMobile($new);
		$old = self::filterMobile($old);

		if($new == $old)
		{
			return false;
		}

		if(!$old && !$new)
		{
			return false;
		}

		if(!$new)
		{
			return null;
		}

		$new = implode("\n", $new);
		return $new;
	}

	private static function filterMobile($_data)
	{
		$_data = explode("\n", $_data);
		$result = [];
		foreach ($_data as $key => $value)
		{
			$temp = trim($value);
			if(ctype_digit($temp))
			{
				$temp = \dash\utility\filter::mobile($temp);
				if($temp)
				{
					$result[] = $temp;
				}
			}
		}

		$result = array_unique($result);
		$result = array_filter($result);

		return $result;
	}



	/**
	 * ready data of survey to load in api
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

				case 'lang':
					$result[$key] = $value;
					if(isset($value))
					{
						$myId = null;
						if(isset($_data['id']))
						{
							$myId = \dash\coding::encode($_data['id']);
						}

						$new_url = \dash\url::base();

						if($value !== \dash\language::primary())
						{
							$new_url .= '/'. $value. '/s/'. $myId;
						}
						else
						{
							$new_url .= '/s/'. $myId;
						}
						$result['s_url'] = $new_url;
					}
					break;

				case 'brandingmeta':
				case 'welcomemedia':
				case 'thankyoumedia':
				case 'setting':
					$result[$key] = json_decode($value, true);
					break;

				default:
					$result[$key] = $value;
					break;
			}
		}

		return $result;
	}


	public static function duplicate($_id)
	{
		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

		$load = \lib\db\surveys::get(['id' => $id, 'limit' => 1]);
		if(!isset($load['id']))
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

		$title = isset($load['title']) ? $load['title'] : "";
		$copy_title = $title. '-copy';

		$load_copy = \lib\db\surveys::get(['title' => $copy_title, 'user_id' => $load['user_id'], 'limit' => 1]);
		if(isset($load_copy['id']))
		{
			\dash\notif::error(T_("Please rename old duplicate survey to make duplicate it again"));
			return false;
		}

		$ok = \lib\db\surveys::duplicate($id);
		if($ok)
		{
			\dash\notif::ok(T_("The survey was duplicated"));
			return true;
		}
		else
		{
			\dash\notif::ok(T_("Can not duplicate this survey"));
			return true;
		}

	}

}
?>
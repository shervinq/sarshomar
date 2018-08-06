<?php
namespace lib\app\poll;

trait edit
{
	/**
	 * edit a poll
	 *
	 * @param      <type>   $_args  The arguments
	 *
	 * @return     boolean  ( description_of_the_return_value )
	 */
	public static function edit($_args, $_id)
	{
		\dash\app::variable($_args);

		$result = self::get($_id);

		if(!$result)
		{
			return false;
		}

		$id = \dash\coding::decode($_id);

		$args = self::check($id);

		if($args === false || !\dash\engine\process::status())
		{
			return false;
		}

		if(!\dash\app::isset_request('title')) 				unset($args['title']);
		if(!\dash\app::isset_request('language')) 			unset($args['lang']);
		if(!\dash\app::isset_request('password')) 			unset($args['password']);
		if(!\dash\app::isset_request('privacy')) 			unset($args['privacy']);
		if(!\dash\app::isset_request('status')) 			unset($args['status']);
		if(!\dash\app::isset_request('branding')) 			unset($args['branding']);
		if(!\dash\app::isset_request('brandingtitle')) 		unset($args['brandingtitle']);
		if(!\dash\app::isset_request('brandingdesc')) 		unset($args['brandingdesc']);
		if(!\dash\app::isset_request('brandingmeta')) 		unset($args['brandingmeta']);
		if(!\dash\app::isset_request('redirect')) 			unset($args['redirect']);
		if(!\dash\app::isset_request('progresbar')) 		unset($args['progresbar']);
		if(!\dash\app::isset_request('trans')) 				unset($args['trans']);
		if(!\dash\app::isset_request('email')) 				unset($args['email']);
		if(!\dash\app::isset_request('emailtitle')) 		unset($args['emailtitle']);
		if(!\dash\app::isset_request('emailto')) 			unset($args['emailto']);
		if(!\dash\app::isset_request('emailmsg')) 			unset($args['emailmsg']);
		if(!\dash\app::isset_request('wellcometitle')) 		unset($args['wellcometitle']);
		if(!\dash\app::isset_request('wellcomedesc')) 		unset($args['wellcomedesc']);
		if(!\dash\app::isset_request('wellcomemedia')) 		unset($args['wellcomemedia']);
		if(!\dash\app::isset_request('thankyoutitle')) 		unset($args['thankyoutitle']);
		if(!\dash\app::isset_request('thankyoudesc')) 		unset($args['thankyoudesc']);
		if(!\dash\app::isset_request('thankyoumedia')) 		unset($args['thankyoumedia']);


		if(!empty($args))
		{
			$update = \lib\db\polls::update($args, $id);

			if(\dash\engine\process::status())
			{
				\dash\log::db('editPoll', ['data' => $id, 'datalink' => \dash\coding::encode($id)]);
				\dash\notif::ok(T_("Poll successfully updated"));
			}
		}
	}
}
?>
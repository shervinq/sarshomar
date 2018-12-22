<?php
namespace lib\app\survey;

trait edit
{
	/**
	 * edit a survey
	 *
	 * @param      <type>   $_args  The arguments
	 *
	 * @return     boolean  ( description_of_the_return_value )
	 */
	public static function edit($_args, $_id)
	{
		\dash\app::variable($_args, ['raw_field' => self::$raw_field]);

		$id = \dash\coding::decode($_id);
		if(!$id)
		{
			\dash\notif::error(T_("Invalid id"));
			return false;
		}

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
		if(!\dash\app::isset_request('welcometitle')) 		unset($args['welcometitle']);
		if(!\dash\app::isset_request('welcomedesc')) 		unset($args['welcomedesc']);
		if(!\dash\app::isset_request('welcomemedia')) 		unset($args['welcomemedia']);
		if(!\dash\app::isset_request('thankyoutitle')) 		unset($args['thankyoutitle']);
		if(!\dash\app::isset_request('thankyoudesc')) 		unset($args['thankyoudesc']);
		if(!\dash\app::isset_request('thankyoumedia')) 		unset($args['thankyoumedia']);
		if(!\dash\app::isset_request('desc'))		 		unset($args['desc']);

		if(!empty($args))
		{
			if(array_key_exists('status', $args))
			{
				$old_detail = \lib\db\surveys::get(['id' => $id, 'limit' => 1]);
				if($args['status'] === 'publish')
				{
					if(isset($old_detail['status']) && $old_detail['status'] !== 'publish')
					{
						$log =
						[
							'user_id' => \dash\user::id(),
							'code'    => $_id,
							'ttitle'  => isset($old_detail['title']) ? $old_detail['title'] : null,
						];

						\dash\log::temp_set('surveyPublished', $log);
					}
				}
			}

			$update = \lib\db\surveys::update($args, $id);

			if(\dash\engine\process::status())
			{
				\dash\log::save_temp();
				\dash\log::set('editSurvay', ['code' => $id, 'datalink' => \dash\coding::encode($id)]);
				\dash\notif::ok(T_("Survay successfully updated"));
			}
		}
	}
}
?>
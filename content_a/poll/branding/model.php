<?php
namespace content_a\poll\branding;


class model
{
	public static function post()
	{

		$post                        = [];
		$post['branding']            = \dash\request::post('branding');

		if($post['branding'])
		{
			$post['brandingtitle']       = \dash\request::post('brandingtitle');
			$post['brandingdesc']        = \dash\request::post('brandingdesc');
			$post['brandingmeta']['url'] = \dash\request::post('brandingurl') ? $_POST['brandingurl'] : null;

			$file = \dash\app\file::upload_quick('brandingfile');

			if($file === false)
			{
				return false;
			}

			if($file)
			{
				$post['brandingmeta']['file']  = $file;
			}
			else
			{
				$old = \content_a\poll\view::load();
				if(isset($old['brandingmeta']['file']))
				{
					$post['brandingmeta']['file']  = $old['brandingmeta']['file'];
				}
			}
		}

		$result = \lib\app\poll::edit($post, \dash\request::get('id'));

		if(\dash\engine\process::status())
		{
			\dash\redirect::pwd();
		}
	}
}
?>

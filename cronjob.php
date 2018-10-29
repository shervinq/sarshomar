<?php
if(file_exists('../dash/lib/engine/cronjob/run.php'))
{
	require_once('../dash/lib/engine/cronjob/run.php');
}
elseif (file_exists('../../dash/lib/engine/cronjob/run.php'))
{
	require_once('../../dash/lib/engine/cronjob/run.php');
}
?>
<?php
chdir(__DIR__);
if(is_file('./dash/lib/engine/cronjob/run.php'))
{
	require_once('./dash/lib/engine/cronjob/run.php');
}
elseif (is_file('../dash/lib/engine/cronjob/run.php'))
{
	require_once('../dash/lib/engine/cronjob/run.php');
}
?>
<?php

if (!isset($_GET['key']) || $_GET['key'] != '6e07753525f49cca750dc395ad0a2565') die('Invalid key');

$scripts = array(
	__DIR__.'/../app/console cache:clear --env=prod',
	__DIR__.'/../app/console assetic:dump --env=prod'
);

echo '<pre>';
foreach ($scripts as $script)
{
	echo "[run]: ".$script ."\n";
	echo shell_exec($script);
	echo "\n\n";
}
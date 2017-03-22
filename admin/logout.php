<?php
//header("Location: http://.@yourdomain.com/log.php");
require '../vendor/autoload.php';

use hornherzogen\AdminHelper;

$adminHelper = new AdminHelper();

header("Location: " . trim(str_replace("/logout.php", "", $adminHelper->thisPageUrl())) . "/log.php");


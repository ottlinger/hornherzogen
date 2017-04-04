<?php
require '../vendor/autoload.php';

use hornherzogen\chart\ChartHelper;

$chartHelper = new ChartHelper();

echo $chartHelper->getByGender();
?>

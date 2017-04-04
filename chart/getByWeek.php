<?php
require '../vendor/autoload.php';

use hornherzogen\chart\ChartHelper;

$chartHelper = new ChartHelper();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['week'])) {
    $week = $formHelper->filterUserInput($_GET['week']);
    echo $chartHelper->getByWeek($week);
}
?>

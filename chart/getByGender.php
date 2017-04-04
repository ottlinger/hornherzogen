<?php
require '../vendor/autoload.php';

use hornherzogen\chart\ChartHelper;
use hornherzogen\FormHelper;

$chartHelper = new ChartHelper();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['week'])) {
    $formHelper = new FormHelper();
    $week = $formHelper->filterUserInput($_GET['week']);
    echo $chartHelper->getByGender($week);
} else {
    echo $chartHelper->getByGender();
}
?>

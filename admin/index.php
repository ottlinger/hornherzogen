<!DOCTYPE html>
<?php
require '../vendor/autoload.php';

use hornherzogen\AdminHelper;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;

$adminHelper = new AdminHelper();
$localizer = new HornLocalizer();
$formHelper = new FormHelper();

$week = NULL;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['week'])) {
    $week = $formHelper->filterUserInput($_GET['week']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['week'])) {
    $week = $formHelper->filterUserInput($_POST['week']);
}
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 Admin">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../favicon.ico">

    <title>Herzogenhorn Adminbereich - Startseite</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="../css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">
    <link href="../css/theme.css" rel="stylesheet">

    <!-- Calendar-related stuff -->
    <link href="../css/bootstrap-formhelpers.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-tree-conifer"></span>
                <?php echo $localizer->i18n('MENU.MAIN'); ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><span
                                class="glyphicon glyphicon-briefcase"></span>
                        <?php echo $localizer->i18n('MENU.ADMIN'); ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="db/db_applicants.php">eingegangene Anmeldungen</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Verwaltung</li>
                        <li><a href="db/db_applicantsWishes.php">Zimmerwünsche der Teilnehmer</a></li>
                        <li><a href="db/db_book.php?id=1&week=1">Buchungen der Zimmer vornehmen</a></li>
                        <li><a href="#">TODO Raumplan pro Woche</a></li>
                        <li><a href="#">TODO Bewerbungsstatus ändern / Kontoverbindung aussenden</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Listen</li>
                        <li><a href="db/db_applicants.php">eingegangene Anmeldungen</a></li>
                        <li><a href="db/db_applicants.php">TODO eingegangene Anmeldungen nach Flexibilität #77</a></li>
                        <li><a href="db/db_applicantsFoodlist.php">Essensliste pro Woche</a></li>
                        <li><a href="db/db_roomsByWeek.php">Raumverteilung pro Woche</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Prüfung</li>
                        <li><a href="db/db_booking_errors.php">Fehlerübersicht Raumbuchungen</a></li>
                    </ul>
                </li>
                <?php echo $adminHelper->showSuperUserMenu(); ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><?php echo $adminHelper->showUserLoggedIn(); ?></li>
                <?php echo $adminHelper->showLogoutMenu(); ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container -->
</nav>

<div class="container theme-showcase">
    <div class="starter-template">
        <?php echo new hornherzogen\ui\ForkMe(); ?>

        <h1>
            <span class="glyphicon glyphicon-sunglasses"></span> Willkommen auf den Startseiten für die
            Herzogenhornanmeldungen des Jahres <?php echo $localizer->i18n('CONST.YEAR'); ?>
            <h2 class="lead">Oben auswählen, was man machen will.</h2>
        </h1>
        <hr/>

        <form class="form-horizontal" method="post"
              action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <div class="form-group">
                <label class="col-sm-2 control-label" for="week">Welche Woche zeigen?
                    <?php
                    // filter for week?
                    if (strlen($week)) {
                        echo strlen($week) ? "(aktiv Woche " . $week . ")" : "";
                    }
                    ?>
                </label>
                <div class="col-sm-10">
                    <select class="form-control" id="week" name="week" onchange="this.form.submit()">
                        <option value="">beide</option>
                        <option value="1" <?php if (isset($week) && 1 == $week) echo ' selected'; ?>>1.Woche</option>
                        <option value="2" <?php if (isset($week) && 2 == $week) echo ' selected'; ?>>2.Woche</option>
                    </select>
                </div>
            </div>
            <noscript>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default btn-primary" title="Submit">Submit</button>
                    </div>
                </div>
            </noscript>
        </form>
        <h2>Wochenauslastung</h2>
        <div id="gauge_div" style="width:280px; height: 140px;"></div>

        <h2>Aufteilung nach Herkunftsländern</h2>
        <div id="piechart_country"></div>
        <h2>Aufteilung nach Geschlecht</h2>
        <div id="piechart_gender"></div>
    </div><!-- /.starter-template -->
</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="../js/bootstrap-formhelpers.min.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!--script type="text/javascript">
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {

        var jsonData = $.ajax({
            url: "../chart/getByGender.php?week=<?php echo $formHelper->filterUserInput($week); ?>",
            dataType: "json",
            async: false
        }).responseText;

        var jsonDataCountry = $.ajax({
            url: "../chart/getByCountry.php?week=<?php echo $formHelper->filterUserInput($week); ?>",
            dataType: "json",
            async: false
        }).responseText;

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);
        var dataCountry = new google.visualization.DataTable(jsonDataCountry);

        var options = {
//            title: 'Applicant distribution',
            is3D: true,
            width: 600,
            height: 500
        };

        var chart1 = new google.visualization.PieChart(document.getElementById('piechart_gender'));
        chart1.draw(data, options);

        var chart2 = new google.visualization.PieChart(document.getElementById('piechart_country'));
        chart2.draw(dataCountry, options);

    }
</script-->
<script type="text/javascript">
    google.charts.load('current', {'packages':['gauge']});
    google.charts.setOnLoadCallback(drawGauge);

    var gaugeOptions = {min: 0, max: 100,
        yellowFrom: 45, yellowTo: 65,
        redFrom: 65, redTo: 100, minorTicks: 5};
    var gauge;

    function drawGauge() {
        gaugeData = new google.visualization.DataTable();
        gaugeData.addColumn('number', 'Engine');
        gaugeData.addRows(2);
        gaugeData.setCell(0, 0, 99);

        gauge = new google.visualization.Gauge(document.getElementById('gauge_div'));
        gauge.draw(gaugeData, gaugeOptions);
    }
</script>
</body>
</html>

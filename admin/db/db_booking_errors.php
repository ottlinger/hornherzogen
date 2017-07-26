<!DOCTYPE html>
<?php
require '../../vendor/autoload.php';

use hornherzogen\AdminHelper;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\BookingErrorChecker;
use hornherzogen\HornLocalizer;

$adminHelper = new AdminHelper();
$localizer = new HornLocalizer();
$errorChecker = new BookingErrorChecker();
$applicantReader = new ApplicantDatabaseReader();
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 - Fehler bei Raumbuchungen ermitteln">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../../favicon.ico">

    <title>Herzogenhorn Adminbereich - Fehler bei Raumbuchungen</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="../../css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/starter-template.css" rel="stylesheet">
    <link href="../../css/theme.css" rel="stylesheet">

    <!-- Calendar-related stuff -->
    <link href="../../css/bootstrap-formhelpers.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

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
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../"><span
                                class="glyphicon glyphicon-briefcase"></span> <?php echo $localizer->i18n('MENU.ADMIN'); ?>
                    </a>
                </li>
                <?php $adminHelper->showSuperUserMenu(); ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><?php echo $adminHelper->showUserLoggedIn(); ?></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container -->
</nav>

<div class="container theme-showcase">
    <div class="starter-template">
        <?php echo new hornherzogen\ui\ForkMe(); ?>

        <h1>
            <span class="glyphicon glyphicon-sunglasses"></span> Prüfungen der Raumbuchungen
        </h1>

        <p>
            <?php
            $config = new ConfigurationWrapper();
            $week = NULL;

            if ($config->isValidDatabaseConfig()) {

                echo "<h2>Doppelte Buchungen pro Person</h2>";
                $applicants = $errorChecker->listDoubleBookings();

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo "<thead>";
                echo "<tr>";
                echo "<th>ApplicantId</th>";
                echo "<th>Vorname</th>";
                echo "<th>Name</th>";
                echo "<th>Anzahl Buchungen</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if(empty($applicants)) {
                    echo "<tr><td colspan='4'>keine vorhanden</td></tr>";
                }

                foreach ($applicants as $row) {
                    $applicantId = $row['applicantId'];
                    $applicant = $applicantReader->getById($applicantId)[0];
                    echo "<tr>";
                    echo "<td><a target=\"_blank\" href=\"db_applicant.php?id=" . $applicantId . "\">#" . $applicantId . "</a></td>";
                    echo "<td>" . $applicant->getFirstname() . "</td>";
                    echo "<td>" . $applicant->getLastname() . "</td>";
                    echo "<td>" . $row['count'] . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";

                echo "<h2>Buchungen, die überbucht sind</h2>";
                $applicants = $errorChecker->listOverbookedBookings();

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo "<thead>";
                echo "<tr>";
                echo "<th>RoomId</th>";
                echo "<th>Zimmer</th>";
                echo "<th>vorgenommene Buchungen</th>";
                echo "<th>erlaubte Buchungen/Raumkapazität</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if(empty($applicants)) {
                    echo "<tr><td colspan='4'>keine vorhanden</td></tr>";
                }

                foreach ($applicants as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['roomId'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['bookingcount'] . "</td>";
                    // Issue #96: add 2 for both weeks
                    echo "<td>" . (2 * $row['capacity']) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";

                echo "<h2>Personen, die storniert haben, abgelehnt wurden oder Spam sind und dennoch eine Zimmerbuchung haben</h2>";
                $applicants = $errorChecker->listPeopleWithBookingsThatDoNotTakePartInTheSeminar();

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo "<thead>";
                echo "<tr>";
                echo "<th>Id</th>";
                echo "<th>kompletter Name</th>";
                echo "<th>Woche</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if(empty($applicants)) {
                    echo "<tr><td colspan='3'>keine vorhanden</td></tr>";
                }
                foreach ($applicants as $row) {
                    $applicantId = $row->getPersistenceId();
                    echo "<tr>";
                    echo "<td><a target=\"_blank\" href=\"db_applicant.php?id=" . $applicantId . "\">#" . $applicantId . "</a></td>";
                    echo "<td>" . $row->getWeek() . "</td>";
                    echo "<td>" . $row->getFullName() . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";
                echo "<p>Bitte etwaige Buchungen über den Raum aus der Buchungsliste entfernen!</p>";

                echo "<h2>Gebuchte Personen ohne Raumreservierung</h2>";
                $applicants = $errorChecker->listPeopleWithFinalStateButNoRooms();

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo "<thead>";
                echo "<tr>";
                echo "<th>ApplicantId</th>";
                echo "<th>Vorname</th>";
                echo "<th>Name</th>";
                echo "<th>Dojo</th>";
                echo "<th>Stadt</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if(empty($applicants)) {
                    echo "<tr><td colspan='5'>keine vorhanden</td></tr>";
                }

                foreach ($applicants as $applicant) {
                    echo "<tr>";
                    $applicantId = $applicant->getPersistenceId();
                    echo "<td><a target=\"_blank\" href=\"db_applicant.php?id=" . $applicantId . "\">#" . $applicantId . "</a></td>";
                    echo "<td>" . $applicant->getFirstname() . "</td>";
                    echo "<td>" . $applicant->getLastname() . "</td>";
                    echo "<td>" . $applicant->getDojo() . "</td>";
                    echo "<td>" . $applicant->getCity() . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table></div>";
                echo "<p>Bitte den Status der Personen umbuchen oder Räume nachbuchen!</p>";

            } else {
                echo "<p>You need to edit your database-related parts of the configuration in order to properly connect to the database.</p>";
            }
            ?>
    </div><!-- /.starter-template -->
</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="../../js/bootstrap-formhelpers.min.js"></script>
</body>
</html>

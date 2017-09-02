<!DOCTYPE html>
<?php
require '../../vendor/autoload.php';

use hornherzogen\AdminHelper;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\RoomDatabaseReader;
use hornherzogen\db\RoomDatabaseWriter;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;

$adminHelper = new AdminHelper();
$localizer = new HornLocalizer();
$formHelper = new FormHelper();
$applicantReader = new ApplicantDatabaseReader();
$config = new ConfigurationWrapper();
$roomReader = new RoomDatabaseReader();
$roomWriter = new RoomDatabaseWriter();

// depending on the way we are called we decide which id to use
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $formHelper->filterUserInput($_POST['id']);
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $formHelper->filterUserInput($_GET['id']);
}

// die if we are called with crapy parameters
if (!isset($id)) {
    echo 'Page called with invalid params - try again from the admin area!';
    die();
}

$week = null;
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['week'])) {
    $week = $formHelper->filterUserInput($_GET['week']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['week'])) {
    $week = $formHelper->filterUserInput($_POST['week']);
}

// prevent double submits
$makeItSo = null;
if ($formHelper->isSetAndNotEmptyInArray($_POST, 'makeItSo')) {
    $makeItSo = $formHelper->filterUserInput($_POST['makeItSo']);
}
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 Anmeldung Raumbuchungsmaske">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../../favicon.ico">

    <title>Herzogenhorn Adminbereich - Raumbuchungsmaske</title>

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
            <span class="glyphicon glyphicon-bed"></span>
            <?php
            $roomsRead = $roomReader->getRoomById($id);

            if (isset($roomsRead) && count($roomsRead) > 0) {
                $room = $roomsRead[0];
                echo "Buchungen für $room[capacity]er $room[name] (DB#$room[id])";
            } else {
                echo 'Bitte Auswahlboxen bedienen';
            }
            ?>
        </h1>

        <p>
            <?php
            if ($config->isValidDatabaseConfig()) {
                ?>

        <form class="form-horizontal" method="post"
              action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <input type="hidden" value="<?php echo $id; ?>" name="id"/>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="week">Welche Woche zeigen?
                    <?php
                    // filter for week?
                    if (strlen($week)) {
                        echo strlen($week) ? '(aktiv Woche '.$week.')' : '';
                    } ?>
                </label>
                <div class="col-sm-10">
                    <select class="form-control" id="week" name="week" onchange="this.form.submit()">
                        <option value="">beide</option>
                        <option value="1" <?php if (isset($week) && 1 == $week) {
                        echo ' selected';
                    } ?>>1.Woche</option>
                        <option value="2" <?php if (isset($week) && 2 == $week) {
                        echo ' selected';
                    } ?>>2.Woche</option>
                    </select>
                </div>
            </div>

            <?php
            $capacityOfSelectedRoom = 0;
                // we do not take any capacity into account since bookings cannot be persisted if a room is overbooked
                $rooms = $roomReader->listRooms();
                echo '<h3><span class="glyphicon glyphicon-dashboard"></span> verfügbare Räume in der Woche: '.count($rooms).'</h3>'; ?>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="id">Welchen Raum bebuchen?</label>
                <div class="col-sm-10">
                    <select class="form-control" id="id" name="id" onchange="this.form.submit()">
                        <?php
                        foreach ($rooms as $oneRoom) {
                            $roomId = $oneRoom['id'];
                            $selected = ($id == $roomId) ? ' selected' : '';

                            // in order to control the bookable persons save currently available capacity
                            if (boolval($selected)) {
                                $capacityOfSelectedRoom = $oneRoom['capacity'];
                            }
                            echo '<option value="'.$roomId.'" '.$selected.'>'.$oneRoom['name'].' ('.$oneRoom['capacity'].'er)</option>';
                        } ?>
                    </select>
                </div>
            </div>

            <hr/>
            <?php
            $applicants = $roomReader->listApplicantsWithoutBookingsInWeek($week);
                echo '<h3><span class="glyphicon glyphicon-briefcase"></span> Insgesamt noch zu buchende Bewerber für die gewählte Woche: '.count($applicants).'</h3><hr />';

                echo '<h3><span class="glyphicon glyphicon-bullhorn"></span> Für den gewählten Raum sind maximal '.$capacityOfSelectedRoom.' Buchungen zulässig.</h3>';

                for ($personNumber = 1; $personNumber <= $capacityOfSelectedRoom; $personNumber++) {
                    ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="applicantId"><?php echo $personNumber; ?>.Person zu Raum
                        hinzufügen</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="applicantId[<?php echo $personNumber; ?>]"
                                name="applicantId[<?php echo $personNumber; ?>]">
                            <?php
                            if ($formHelper->isSetAndNotEmptyInArray($_POST, 'applicantId') && count($_POST['applicantId']) > $personNumber) {
                                $applicantId = $formHelper->filterUserInput($_POST['applicantId'][$personNumber]);
                            } else {
                                $applicantId = '(none)';
                            }

                    echo '<option value="(none)" '.('(none)' == $applicantId ? ' selected' : '').'>(bitte auswählen)</option>';
                    foreach ($applicants as $applicant) {
                        $appId = $applicant->getPersistenceId();
                        $selected = ($applicantId == $appId) ? ' selected' : '';

                        echo '<option value="'.$appId.'" '.$selected.'>'.$applicant->getFullName().' (#'.$appId.')</option>';
                    } ?>
                        </select>
                    </div>
                </div>
                <?php
                } // end of personSelectBox for
            ?>

            <hr/>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="makeItSo">Wirklich abändern?</label>
                <input class="button" type="radio" name="makeItSo" id="makeItSo" value="true">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-default btn-primary"
                        title="<?php echo $localizer->i18n('FORM.SUBMIT'); ?>"> Personen in Raum einbuchen
                </button>
            </div>

            <noscript>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default btn-primary" title="Submit">Submit</button>
                    </div>
                </div>
            </noscript>
        </form>

    <?php
    // PERFORM BOOKING
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['week']) && isset($_POST['applicantId']) && boolval($makeItSo)) {
        foreach ($_POST['applicantId'] as $submittedApplicantId) {
            $iid = $formHelper->filterUserInput($_POST['id']);

            // skip no selection
            if ('(none)' == $submittedApplicantId) {
                continue;
            }

            if ($roomWriter->canRoomBeBooked($iid)) {
                $persistId = $roomWriter->performBooking($iid, $submittedApplicantId);
                if (null != $persistId) {
                    echo '<p style="color: darkgreen;"><span class="glyphicon glyphicon-send"></span> Buchung angelegt mit id #'.$persistId." für Person mit Id <a href='db_applicant.php?id=".$submittedApplicantId."' target='_blank'>#".$submittedApplicantId.'</a></p>';
                    $_POST['applicantId'] = null;
                }
            } else {
                echo '<p style="color: red;"><span class="glyphicon glyphicon-bell"></span> Kann Raum #'.$iid." für Person <a href='db_applicant.php?id=".$submittedApplicantId."' target='_blank'>#".$submittedApplicantId.'</a> nicht buchen, da Raum sonst überbucht würde.';
            }
        } // end of for
        $_POST['applicantId'] = null;
    }

                // REMOVE BOOKING
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aid']) && ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost')) {
                    $aid = $formHelper->filterUserInput($_POST['aid']);
                    echo $roomWriter->deleteForApplicantId($aid)." Zeilen für Applicant mit id <a href='db_applicant.php?id=".$id."' target='_blank'>#".$id.'</a> gelöscht.';
                    $_POST['aid'] = null;
                }

                echo "<hr/><h3><span class=\"glyphicon glyphicon-wrench\"></span> Für den Raum#$id sind bisher gebucht:</h3>";
                $roomBookings = $roomReader->listBookingsByRoomNumberAndWeek($id, $week);

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo '<thead>';
                echo '<tr>';
                if ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost') {
                    echo '<th>AKTIONEN</th>';
                }
                echo '<th>Anrede</th>';
                echo '<th>Name</th>';
                echo '<th>Dojo</th>';
                echo '<th>Zimmerkategorie</th>';
                echo '<th>Zusammenlegungswunsch</th>';
                echo '<th>Umbuchbar?</th>';
                echo '<th>Anmerkungen</th>';
                echo '<th>Sprache</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($roomBookings as $applicant) {
                    echo '<tr>';

                    if ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost') {
                        echo '<td>
                    <form class="form-horizontal" method="post" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'?aid='.$applicant->getPersistenceId().'&week='.$week.'&id='.$id.'">
                        <input type="hidden" name="aid" value="'.$applicant->getPersistenceId().'"/>
                        <input type="hidden" name="week" value="'.$week.'"/>
                        <input type="hidden" name="id" value="'.$id.'"/>
                        <button type="submit" class="btn btn-default btn-danger" title="Entfernen">Entferne Person #'.$applicant->getPersistenceId().' aus Raum</button>
                    </form>
                </td>';
                    }

                    echo '<td>'.$applicant->getGenderIcon().' '.$applicant->getGender().'</td>';
                    echo '<td>'.$applicant->getFullName().'</td>';
                    echo '<td>'.$applicant->getDojo().' in '.$applicant->getCity().'</td>';
                    echo '<td>'.$applicant->getRoom().'</td>';
                    echo '<td>'.(strlen($applicant->getPartnerOne()) || strlen($applicant->getPartnerTwo()) ? $applicant->getPartnerOne().' / '.$applicant->getPartnerTwo() : 'keiner').'</td>';
                    echo '<td>'.($applicant->getFlexible() ? 'ja' : 'nein').'</td>';
                    echo '<td>'.nl2br($applicant->getRemarks()).'</td>';
                    echo '<td>'.$applicant->getLanguage().'</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table></div>';
            } else {
                echo '<p>You need to edit your database-related parts of the configuration in order to properly connect to the database.</p>';
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

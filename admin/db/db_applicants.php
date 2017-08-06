<!DOCTYPE html>
<?php
require '../../vendor/autoload.php';

use hornherzogen\AdminHelper;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\db\ApplicantDatabaseReader;
use hornherzogen\db\ApplicantDatabaseWriter;
use hornherzogen\db\DatabaseHelper;
use hornherzogen\db\StatusDatabaseReader;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;

$adminHelper = new AdminHelper();
$localizer = new HornLocalizer();
$databaseHelper = new DatabaseHelper();
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 Anmeldung">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="../../favicon.ico">

    <title>Herzogenhorn Adminbereich - Anmeldungen</title>

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
            <span class="glyphicon glyphicon-sunglasses"></span> eingegangene Anmeldungen
        </h1>

        <p>
            <?php
            echo '<h2>nach Status sortiert</h2>';

            $config = new ConfigurationWrapper();
            $week = null;

            if ($config->isValidDatabaseConfig()) {
                try {
                    $db = new PDO('mysql:host='.$config->dbhost().';dbname='.$config->dbname(), $config->dbuser(), $config->dbpassword());
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // STATS
                $q = $db->query('SELECT s.name, a.week, a.statusId, count(*) AS howmany from `status` s, `applicants` a WHERE a.statusId=s.id GROUP BY a.statusId, a.week ORDER BY a.week');
                    $databaseHelper->logDatabaseErrors($q, $db);

                    echo '<div class="table-responsive"><table class="table table-striped">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Week</th>';
                    echo '<th>Current Status</th>';
                    echo '<th>#applicants</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = $q->fetch()) {
                        echo "<tr><td>$row[week]</td><td>$row[name]</td><td>$row[howmany]</td></tr>";
                    }
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } catch (PDOException $e) {
                    echo 'Unable to connect to db:'.$e->getMessage();
                }

                echo '<h2>Als Gesamtliste</h2>'; ?>
        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <div class="form-group">
                <label class="col-sm-2 control-label" for="week">Welche Woche zeigen?
                    <?php
                    // filter for week?
                    $formHelper = new FormHelper();
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['week'])) {
                    $week = $formHelper->filterUserInput($_POST['week']);
                    echo strlen($week) ? '(aktiv Woche '.$week.')' : '';
                } ?>
                </label>
                <div class="col-sm-10">
                    <select class="form-control" id="week" name="week" onchange="this.form.submit()">
                        <option value="">beide</option>
                        <option value="1" <?php if (isset($week) && 1 == $week) {
                    echo ' selected';
                } ?>>1.Woche
                        </option>
                        <option value="2" <?php if (isset($week) && 2 == $week) {
                    echo ' selected';
                } ?>>2.Woche
                        </option>
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

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aid']) && ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost')) {
        $remover = new ApplicantDatabaseWriter();
        $id = $formHelper->filterUserInput($_POST['aid']);
        echo $remover->removeById($id).' Zeile mit id #'.$id.' gelöscht';
        $_POST['aid'] = null;
    }

                $statusReader = new StatusDatabaseReader();

                $reader = new ApplicantDatabaseReader();
                $applicants = $reader->getAllByWeek($week);

                echo '<div class="table-responsive"><table class="table table-striped">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>DB-Id</th>';
                if ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost') {
                    echo '<th>AKTIONEN</th>';
                }
                echo '<th>Woche</th>';
                echo '<th>Sprache</th>';
                echo '<th>Anrede</th>';
                echo '<th>Vorname</th>';
                echo '<th>Nachname</th>';
                echo '<th>Gesamtname</th>';
                echo '<th>Adresse</th>';
                echo '<th>PLZ/Stadt</th>';
                echo '<th>Land</th>';
                echo '<th>E-Mail</th>';
                echo '<th>Dojo</th>';
                echo '<th>Graduierung</th>';
                echo '<th>twa?</th>';
                echo '<th>Zimmer</th>';
                echo '<th>Zusammenlegungswunsch</th>';
                echo '<th>Essen</th>';
                echo '<th>Umbuchbar?</th>';
                echo '<th>Anmerkungen</th>';
                echo '<th>aktueller Status</th>';
                echo '<th>Statusübersicht</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                foreach ($applicants as $applicant) {
                    echo '<tr>';
                    echo "<td><a href='db_applicant.php?id=".$applicant->getPersistenceId()."' target='_blank'>".$applicant->getPersistenceId().'</a></td>';

                    if ($adminHelper->isAdmin() || $adminHelper->getHost() == 'localhost') {
                        echo '<td>
                    <form class="form-horizontal" method="post" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'">
                        <input type="hidden" name="aid" value="'.$applicant->getPersistenceId().'"/>
                        <button type="submit" class="btn btn-default btn-danger" title="Entfernen">Entfernen von #'.$applicant->getPersistenceId().'</button>
                    </form>
                </td>';
                    }

                    echo '<td>'.$applicant->getWeek().'</td>';
                    echo '<td>'.$applicant->getLanguage().'</td>';
                    echo '<td>'.$applicant->getGenderIcon().' '.$applicant->getGender().'</td>';
                    echo '<td>'.$applicant->getFirstname().'</td>';
                    echo '<td>'.$applicant->getLastname().'</td>';
                    echo '<td>'.$applicant->getFullName().'</td>';
                    echo '<td>'.$applicant->getStreet().' '.$applicant->getHouseNumber().'</td>';
                    echo '<td>'.$applicant->getZipCode().' '.$applicant->getCity().'</td>';
                    echo '<td>'.$applicant->getCountry().'</td>';
                    echo '<td>'.$applicant->getEmail().'</td>';
                    echo '<td>'.$applicant->getDojo().'</td>';
                    echo '<td>'.$applicant->getGrading().' seit '.$applicant->getDateOfLastGrading().'</td>';
                    echo '<td>'.(strlen($applicant->getTwaNumber()) ? ' ja,  '.$applicant->getTwaNumber() : 'nein').'</td>';
                    echo '<td>'.$applicant->getRoom().'</td>';
                    echo '<td>'.(strlen($applicant->getPartnerOne()) || strlen($applicant->getPartnerTwo()) ? $applicant->getPartnerOne().' '.$applicant->getPartnerTwo() : 'keiner').'</td>';
                    echo '<td>'.$applicant->getFoodCategory().'</td>';
                    echo '<td>'.($applicant->getFlexible() ? 'ja' : 'nein').'</td>';
                    echo '<td>'.nl2br($applicant->getRemarks()).'</td>';

                    $statId = $statusReader->getById($applicant->getCurrentStatus());
                    if (isset($statId) && isset($statId[0]) && isset($statId[0]['name'])) {
                        echo '<td>'.$statId[0]['name'].'</td>';
                    } else {
                        echo '<td>'.($applicant->getCurrentStatus() ? $applicant->getCurrentStatus() : 'NONE').'</td>';
                    }

                    echo '<td>';
                    echo 'CREATED: '.$applicant->getCreatedAt().'<br />';
                    echo 'MAILED: '.$applicant->getMailedAt().'<br />';
                    echo 'VERIFIED: '.$applicant->getConfirmedAt().'<br />';
                    echo 'PAYMENTMAILED: '.$applicant->getPaymentRequestedAt().'<br />';
                    echo 'PAYMENTRECEIVED: '.$applicant->getPaymentReceivedAt().'<br />';
                    echo 'BOOKED: '.$applicant->getBookedAt().'<br />';
                    echo 'CANCELLED: '.$applicant->getCancelledAt();
                    echo '</td>';
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

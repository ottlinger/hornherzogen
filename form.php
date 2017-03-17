<!DOCTYPE html>
<?php require 'vendor/autoload.php';
use hornherzogen\ApplicantInput;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;
use hornherzogen\SubmitMailer;

$config = new \hornherzogen\ConfigurationWrapper();
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?php echo HornLocalizer::i18n('FORM.TITLE'); ?>">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="favicon.ico">

    <title><?php echo HornLocalizer::i18n('FORM.TITLE'); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="./css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/starter-template.css" rel="stylesheet">
    <link href="./css/theme.css" rel="stylesheet">

    <!-- Calendar-related stuff -->
    <link href="./css/bootstrap-formhelpers.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]>
    <script src="./assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./assets/js/ie-emulation-modes-warning.js"></script>

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
                <span class="sr-only"><?php echo HornLocalizer::i18n('NAV.TOGGLE'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./index.php"><span class="glyphicon glyphicon-tree-conifer"></span>
                <?php echo HornLocalizer::i18n('MENU.MAIN'); ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="./form.php"><span
                                class="glyphicon glyphicon-home"></span> <?php echo HornLocalizer::i18n('MENU.APPLY'); ?>
                    </a></li>
                <li><a href="./contact.php"><span
                                class="glyphicon glyphicon-envelope"></span> <?php echo HornLocalizer::i18n('MENU.FAQ'); ?>
                    </a></li>
                <li><a href="./admin" target="_blank"><span
                                class="glyphicon glyphicon-briefcase"></span> <?php echo HornLocalizer::i18n('MENU.ADMIN'); ?>
                    </a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container -->
</nav>

<div class="container theme-showcase">
    <div class="starter-template">
        <a href="https://github.com/ottlinger/hornherzogen" target="_blank"><img
                    style="position: absolute; top: 100px; right: 0; border: 0;"
                    src="https://camo.githubusercontent.com/e7bbb0521b397edbd5fe43e7f760759336b5e05f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677265656e5f3030373230302e706e67"
                    alt="Fork me on GitHub"
                    data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png"></a>
        <h1>
            <span class="glyphicon glyphicon-sunglasses"></span> <?php echo HornLocalizer::i18n('FORM.TITLE'); ?>
        </h1>

        <?php
        // we always have an empty container for user input data
        $applicantInput = new ApplicantInput();
        $formHelper = new FormHelper();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $applicantInput->parse();

            if ($config->debug()) {
                echo '<h2>Language setting is: ' . HornLocalizer::getLanguage() . '</h2>';
                echo '<pre>';
                echo '<p>RAW data after submit:</p>';
                var_dump(file_get_contents('php://input'));
                echo '<p>Converted to POST:</p>';
                var_dump($_POST);
                echo '<p>' . $applicantInput->__toString();
                echo '</p><p>' . var_dump($applicantInput);
                echo '</p>';
                echo '</pre>';
            } // if debug
        } // if POST
        ?>
        <?php if ($applicantInput->hasErrors() || $applicantInput->hasParseErrors()) { ?>
        <p class="lead"><?php echo HornLocalizer::i18n('FORM.INTRO.LINE1'); ?><br/>
            <?php echo HornLocalizer::i18n('FORM.INTRO.LINE2'); ?>
        </p>
        <p><?php echo HornLocalizer::i18nParams('TIME', $formHelper->timestamp()); ?></p>

        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <?php if ($applicantInput->hasParseErrors()) {
                echo "<p class=\"lead \"><span class=\"glyphicon glyphicon-warning-sign\"></span> " . HornLocalizer::i18nParams('FORM.ERROR_MESSAGE', $applicantInput->getErrorCount()) . "</p>";
            } // show error message ?>

            <?php } else { ?>
                <p class="lead"><span class="glyphicon glyphicon-envelope"></span> <?php echo HornLocalizer::i18n('FORM.SUC.CHECK'); ?></p>
                <p><?php echo HornLocalizer::i18nParams('TIME', $formHelper->timestamp()); ?></p>
                <?php
                // send mail only if there are no error messages and nothing already exists in the database
                $sender = new SubmitMailer($applicantInput);

                if (!$sender->existsInDatabase()) {
                    echo $sender->send();
                    echo $sender->sendInternally();
                    echo "<p>" . HornLocalizer::i18nParams('FORM.SAVEDAS', $sender->saveInDatabase()) . "</p>";
                }
            } // if showButtons
            ?>

            <legend><?php echo HornLocalizer::i18n(''); ?>Bitte die gewünschte Lehrgangswoche auswählen</legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('week'); ?>">
                <label class="col-sm-2 control-label" for="week"><?php echo HornLocalizer::i18n(''); ?>Welche Woche (*)</label>
                <div class="col-sm-10">
                    <select class="form-control" id="week" name="week">
                        <option value="week1" <?php if ('1' == $applicantInput->getWeek()) echo ' selected'; ?>><?php echo HornLocalizer::i18n(''); ?>1.Woche
                            - ab Sonntag, den 2017-06-18
                        </option>
                        <option value="week2" <?php if ('2' == $applicantInput->getWeek()) echo ' selected'; ?><?php echo HornLocalizer::i18n(''); ?>>2.Woche
                            - ab Sonntag, den 2017-06-25
                        </option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('week'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('flexible'); ?>">
                <label class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>
                    Kann ich im Fall einer Überbuchung in die andere Woche ausweichen? (*)</label>
                <div class="col-sm-10">
                    <div class="radio" id="flexible">
                        <label>
                            <input type="radio" name="flexible" id="no"
                                   value="no" <?php if (!$applicantInput->getFlexible()) echo ' checked'; ?>>
                            <?php echo HornLocalizer::i18n(''); ?>Ich kann nur in dieser Woche am Lehrgang teilnehmen.
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="flexible" id="yes"
                                   value="yes" <?php if ($applicantInput->getFlexible()) echo ' checked'; ?>>
                            <?php echo HornLocalizer::i18n(''); ?>Ich bin flexibel, <strong>falls</strong> diese Woche überbucht ist.
                        </label>
                    </div>
                </div>
            </div>

            <legend><?php echo HornLocalizer::i18n(''); ?>Persönliche Daten</legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('gender'); ?>">
                <label for="gender" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Anrede (*)</label>
                <div class="col-sm-10">
                    <select class="form-control" id="gender" name="gender">
                        <option value="male" <?php if ('male' == $applicantInput->getGender()) echo ' selected'; ?>>
                            <?php echo HornLocalizer::i18n(''); ?>Herr
                        </option>
                        <option value="female" <?php if ('female' == $applicantInput->getGender()) echo ' selected'; ?>>
                            <?php echo HornLocalizer::i18n(''); ?>Frau
                        </option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('gender'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('vorname'); ?>">
                <label for="vorname" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Vorname (*)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="vorname" id="vorname"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte Vorname eingeben."
                           value="<?php echo $applicantInput->getFirstname(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('vorname'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('nachname'); ?>">
                <label for="nachname" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Nachname (*)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nachname" id="nachname"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte Nachname eingeben."
                           value="<?php echo $applicantInput->getLastname(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('nachname'); ?>
                </div>
            </div>

            <p><?php echo HornLocalizer::i18n(''); ?>Die Adressdaten benötigen wir zur Ausstellung der Zahlungsaufforderung:</p>

            <div class="form-group <?php echo $applicantInput->getUIResponse('street'); ?>">
                <label for="street" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Straße (*)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="street" id="street"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte die Straße der Postanschrift ohne Hausnummer eingeben."
                           value="<?php echo $applicantInput->getStreet(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('street'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('houseno'); ?>">
                <label for="houseno" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Hausnummer (*)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="houseno" name="houseno"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte die komplette Hausnummer zur Postanschrift eingeben."
                           value="<?php echo $applicantInput->getHouseNumber(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('houseno'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('plz'); ?>">
                <label for="plz" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>PLZ (*)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="plz" id="plz" placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte die PLZ eingeben."
                           value="<?php echo $applicantInput->getZipCode(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('plz'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('city'); ?>">
                <label for="city" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Ort (*)</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="city" id="city"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte den Wohnort eingeben." value="<?php echo $applicantInput->getCity(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('city'); ?>
                </div>
            </div>

            <?php
            /**
             * TODO: issue #38
             * http://bootstrapformhelpers.com/country/#jquery-plugins
             * Available languages
             *
             * English (US)
             * Arabic
             * German (DE)
             * Spanish (ES)
             * Italian (IT)
             * Portuguese (BR)
             * Simplified Chinese (CN)
             * Simplified Chinese (TW)
             * add mapper to Localizer! to get String for SubmitMailer
             *
             * Add method for country preselection if getCountrx is empty:
             * de -> DE
             * en -> GB
             * jp -> JP
             */
            ?>
            <div class="form-group <?php echo $applicantInput->getUIResponse('country'); ?>">
                <label for="country" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>Land (*)</label>
                <div id="country" data-name="country" class="col-sm-10 bfh-selectbox bfh-countries"
                     data-country="<?php echo $applicantInput->getCountry(); ?>" data-flags="true">
                    <?php echo $applicantInput->showSymbolIfFeedback('country'); ?>
                </div>
            </div>

            <p><?php echo HornLocalizer::i18n(''); ?>Zur Zusendung der Anmeldebestätigung benötigen wir eine gültige Mailadresse, bitte gib diese zur
                Sicherheit doppelt ein:</p>
            <div class="form-group <?php echo $applicantInput->getUIResponse('email'); ?>">
                <label for="email" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>E-Mail (*)</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte Mailadresse eingeben."
                           value="<?php echo $applicantInput->getEmail(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('email'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('emailcheck'); ?>">
                <label for="emailcheck" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n(''); ?>E-Mail-Bestätigung (*)</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="emailcheck" id="emailcheck"
                           placeholder="<?php echo HornLocalizer::i18n(''); ?>Bitte gib die Mailadresse nochmals zur Bestätigung ein."
                           value="<?php echo $applicantInput->getEmailcheck(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('emailcheck'); ?>
                </div>
            </div>

            <legend><?php echo HornLocalizer::i18n('FORM.AIKIDO'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('dojo'); ?>">
                <label for="dojo" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.DOJO'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dojo" id="dojo"
                           placeholder="<?php echo HornLocalizer::i18n('FORM.DOJO.PL'); ?>"
                           value="<?php echo $applicantInput->getDojo(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('dojo'); ?>
                </div>
            </div>

            <p><?php echo HornLocalizer::i18n('FORM.TWA.LABEL'); ?></p>
            <div class="form-group <?php echo $applicantInput->getUIResponse('twano'); ?>">
                <label for="twano" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.TWA'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="twano" id="twano"
                           placeholder="<?php echo HornLocalizer::i18n('FORM.TWA.PL'); ?>"
                           value="<?php echo $applicantInput->getTwaNumber(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('twano'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('grad'); ?>">
                <label for="grad" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.GRADING'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" id="grad" name="grad">
                        <option <?php if ('6.Dan' == $applicantInput->getGrading()) echo ' selected'; ?>>6.Dan</option>
                        <option <?php if ('5.Dan' == $applicantInput->getGrading()) echo ' selected'; ?>>5.Dan</option>
                        <option <?php if ('4.Dan' == $applicantInput->getGrading()) echo ' selected'; ?>>4.Dan</option>
                        <option <?php if ('3.Dan' == $applicantInput->getGrading()) echo ' selected'; ?>>3.Dan</option>
                        <option <?php if ('2.Dan' == $applicantInput->getGrading()) echo ' selected'; ?>>2.Dan</option>
                        <option <?php if ('1.Dan' == $applicantInput->getGrading() || !strlen($applicantInput->getGrading())) echo ' selected'; ?>>
                            1.Dan
                        </option>
                        <option> <?php if ('1.Kyu' == $applicantInput->getGrading()) echo ' selected'; ?>1.Kyu</option>
                        <option> <?php if ('2.Kyu' == $applicantInput->getGrading()) echo ' selected'; ?>2.Kyu</option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('grad'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('gsince'); ?>">
                <label for="gsince" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.GSINCE'); ?></label>
                <div class="col-sm-10">
                    <div class="bfh-datepicker" data-name="gsince" data-format="y-m-d"
                         data-date="<?php if (empty($applicantInput->getDateOfLastGrading())) {
                             echo date('Y-m-d');
                         } else {
                             echo $applicantInput->getDateOfLastGrading();
                         } ?>">
                        <div class="input-prepend bfh-datepicker-toggle" data-toggle="bfh-datepicker">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                            <input type="text" class="input-medium" name="gsince" id="gsince" readonly>
                            <?php echo $applicantInput->showSymbolIfFeedback('gsince'); ?>
                        </div>
                        <div class="bfh-datepicker-calendar">
                            <table class="calendar table table-bordered">
                                <thead>
                                <tr class="months-header">
                                    <th class="month" colspan="4">
                                        <a class="previous" href="#"><i class="icon-chevron-left"></i></a>
                                        <span></span>
                                        <a class="next" href="#"><i class="icon-chevron-right"></i></a>
                                    </th>
                                    <th class="year" colspan="3">
                                        <a class="previous" href="#"><i class="icon-chevron-left"></i></a>
                                        <span></span>
                                        <a class="next" href="#"><i class="icon-chevron-right"></i></a>
                                    </th>
                                </tr>
                                <tr class="days-header">
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <legend><?php echo HornLocalizer::i18n('FORM.ROOM.PL'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('room'); ?>">
                <label class="col-sm-2 control-label" for="room"><?php echo HornLocalizer::i18n('FORM.ROOM'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="room" id="room">
                        <option value="3bed" <?php if ('3bed' == $applicantInput->getRoom()) echo ' selected'; ?>><?php echo HornLocalizer::i18n('FORM.ROOM.3'); ?></option>
                        <option value="2bed" <?php if ('2bed' == $applicantInput->getRoom()) echo ' selected'; ?>><?php echo HornLocalizer::i18n('FORM.ROOM.2'); ?></option>
                        <option value="1bed" <?php if ('1bed' == $applicantInput->getRoom()) echo ' selected'; ?>><?php echo HornLocalizer::i18n('FORM.ROOM.1'); ?></option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('room'); ?>
                </div>
            </div>

            <div id="together1-group">
                <p><?php echo HornLocalizer::i18n('FORM.PARTNER'); ?></p>
                <div class="form-group <?php echo $applicantInput->getUIResponse('together1'); ?>">
                    <label for="together1" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.PARTNER.P1'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="together1" id="together1"
                               placeholder="<?php echo HornLocalizer::i18n('FORM.PARTNER.PL'); ?>"
                               value="<?php echo $applicantInput->getPartnerOne(); ?>"/>
                        <?php echo $applicantInput->showSymbolIfFeedback('together1'); ?>
                    </div>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('together2'); ?>" id="together2-group">
                <label for="together2" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.PARTNER.P2'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="together2" id="together2"
                           placeholder="<?php echo HornLocalizer::i18n('FORM.PARTNER.PL'); ?>"
                           value="<?php echo $applicantInput->getPartnerTwo(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('together2'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('essen'); ?>">
                <label class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.FOOD'); ?></label>
                <div class="col-sm-10">
                    <div class="radio" id="essen">
                        <label>
                            <input type="radio" name="essen" id="meat"
                                   value="meat" <?php if ('meat' == $applicantInput->getFoodCategory()) echo ' checked'; ?>>
                            <?php echo HornLocalizer::i18n('FORM.FOOD.MEAT'); ?>
                        </label>
                        <label>
                            <input type="radio" name="essen" id="veg"
                                   value="veg" <?php if ('veg' == $applicantInput->getFoodCategory()) echo ' checked'; ?>>
                            <?php echo HornLocalizer::i18n('FORM.FOOD.VEG'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <legend><?php echo HornLocalizer::i18n('FORM.OTHER'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('additionals'); ?>">
                <label for="additionals" class="col-sm-2 control-label"><?php echo HornLocalizer::i18n('FORM.REMARK'); ?></label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="additionals" id="additionals" rows="13" maxlength="400"
                              placeholder="<?php echo HornLocalizer::i18n('FORM.REMARK.PL'); ?>"><?php echo $applicantInput->getRemarks(); ?></textarea>
                    <?php echo $applicantInput->showSymbolIfFeedback('additionals'); ?>
                </div>
            </div>

            <?php if ($applicantInput->showFormButtons()) { ?>
            <p class="lead"><?php echo HornLocalizer::i18n('FORM.MANDATORYFIELDS') ?></p>
            <div class="form-group">
                <button type="submit" class="btn btn-default btn-primary"
                        title="<?php echo HornLocalizer::i18n('FORM.SUBMIT'); ?>"><?php echo HornLocalizer::i18n('FORM.SUBMIT'); ?></button>
                <button type="reset" class="btn btn-danger"
                        title="<?php echo HornLocalizer::i18n('FORM.RESET'); ?>"><?php echo HornLocalizer::i18n('FORM.RESET'); ?></button>
            </div>
        </form>
    <?php } // buttonIf ?>

    </div><!-- /.starter-template -->
</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="./assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="./js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="./js/bootstrap-formhelpers.min.js"></script>
<script src="./js/app.js"></script>
</body>
</html>

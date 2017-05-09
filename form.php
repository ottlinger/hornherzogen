<!DOCTYPE html>
<?php require 'vendor/autoload.php';
use hornherzogen\ApplicantInput;
use hornherzogen\ConfigurationWrapper;
use hornherzogen\FormHelper;
use hornherzogen\HornLocalizer;
use hornherzogen\mail\SubmitMailer;

$config = new ConfigurationWrapper();
$hornlocalizer = new HornLocalizer();
$formHelper = new FormHelper();

// special handling to allow submission after end of submission
$isMagic = false;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['isMagic'])) {
    $isMagic = boolval($formHelper->filterUserInput($_GET['isMagic']));
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['isMagic'])) {
    $isMagic = boolval($formHelper->filterUserInput($_POST['isMagic']));
}
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="<?php echo $hornlocalizer->i18n('FORM.TITLE'); ?>">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="favicon.ico">

    <title><?php echo $hornlocalizer->i18n('FORM.TITLE'); ?></title>

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
                <span class="sr-only"><?php echo $hornlocalizer->i18n('NAV.TOGGLE'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./index.php"><span class="glyphicon glyphicon-tree-conifer"></span>
                <?php echo $hornlocalizer->i18n('MENU.MAIN'); ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="./form.php"><span
                                class="glyphicon glyphicon-home"></span> <?php echo $hornlocalizer->i18n('MENU.APPLY'); ?>
                    </a></li>
                <li><a href="./contact.php"><span
                                class="glyphicon glyphicon-envelope"></span> <?php echo $hornlocalizer->i18n('MENU.FAQ'); ?>
                    </a></li>
                <li><a href="./admin"><span
                                class="glyphicon glyphicon-briefcase"></span> <?php echo $hornlocalizer->i18n('MENU.ADMIN'); ?>
                    </a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-education"></span> <?php echo date('Y-m-d H:i:s'); ?>
                    </a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container -->
</nav>

<div class="container theme-showcase">
    <div class="starter-template">
        <?php echo new hornherzogen\ui\ForkMe(); ?>
        <h1>
            <span class="glyphicon glyphicon-sunglasses"></span> <?php echo $hornlocalizer->i18n('FORM.TITLE'); ?>
        </h1>

        <?php
        // we always have an empty container for user input data
        $applicantInput = new ApplicantInput();

        if ($formHelper->isSubmissionClosed($config)) {
            echo "<h1 style=\"color: red; font-weight: bold;\">" . $hornlocalizer->i18n('SUBMISSIONCLOSED') . "</h1>";
        }

        if (boolval($isMagic)) {
            echo "<h1 style=\"color: red; font-weight: bold;\">FORM SUBMISSION WILL WORK AFTER SUBMISSION IS CLOSED</h1>";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $applicantInput->parse();

            if ($config->debug()) {
                echo '<h2>Language setting is: ' . $hornlocalizer->getLanguage() . '</h2>';
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
        <p class="lead"><?php echo $hornlocalizer->i18n('FORM.INTRO.LINE1'); ?><br/>
            <?php echo $hornlocalizer->i18n('FORM.INTRO.LINE2'); ?>
        </p>
        <p><?php echo $hornlocalizer->i18nParams('TIME', $formHelper->timestamp()); ?></p>

        <form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <?php
            // in order to survive the post we add it as a hidden value
            if (boolval($isMagic)) {
                echo "<input type=\"hidden\" name=\"isMagic\" value=\"yesSir\"/>";
            }

            if ($applicantInput->hasParseErrors()) {
                echo "<p class=\"lead\" style=\"color: red; font-weight: bold;\"><span class=\"glyphicon glyphicon-warning-sign\"></span> " . $hornlocalizer->i18nParams('FORM.ERROR_MESSAGE', $applicantInput->getErrorCount()) . "</p>";
            } // show error message ?>

            <?php } else { ?>
                <p><?php echo $hornlocalizer->i18nParams('TIME', $formHelper->timestamp()); ?></p>
                <?php
                // send mail only if there are no error messages and nothing already exists in the database
                $sender = new SubmitMailer($applicantInput);

                // #103: special case isMagic
                if ((boolval($isMagic) || !$formHelper->isSubmissionClosed($config)) && !$sender->existsInDatabase()) {
                    echo $sender->send();
                    echo $sender->sendInternally();
                    ?>
                    <p class="lead" style="color: darkgreen; font-weight: bold;"><span
                                class="glyphicon glyphicon-envelope"></span> <?php echo $hornlocalizer->i18n('FORM.SUC.CHECK'); ?>
                    </p>
                    <?php
                    echo "<h3 style='color: rebeccapurple; font-weight: bold;'>" . $hornlocalizer->i18nParams('FORM.SAVEDAS', $sender->saveInDatabase()) . "</h3>";
                }
            } // if showButtons
            ?>


            <legend><?php echo $hornlocalizer->i18n('FORM.WEEK'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('week'); ?>">
                <label class="col-sm-2 control-label"
                       for="week"><?php echo $hornlocalizer->i18n('FORM.WEEK.LABEL'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" id="week" name="week">
                        <option value="week1" <?php if ('1' == $applicantInput->getWeek()) echo ' selected'; ?>><?php echo $hornlocalizer->i18n('FORM.WEEK.1'); ?></option>
                        <option value="week2" <?php if ('2' == $applicantInput->getWeek()) echo ' selected'; ?>><?php echo $hornlocalizer->i18n('FORM.WEEK.2'); ?></option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('week'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('flexible'); ?>">
                <label class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.FLEXIBLE'); ?></label>
                <div class="col-sm-10">
                    <div class="radio" id="flexible">
                        <label>
                            <input type="radio" name="flexible" id="no"
                                   value="no" <?php if (!$applicantInput->getFlexible()) echo ' checked'; ?>>
                            <?php echo $hornlocalizer->i18n('FORM.FLEXIBLE.NO'); ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="flexible" id="yes"
                                   value="yes" <?php if ($applicantInput->getFlexible()) echo ' checked'; ?>>
                            <?php echo $hornlocalizer->i18n('FORM.FLEXIBLE.YES'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <legend><?php echo $hornlocalizer->i18n('FORM.PERSONALDATA'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('gender'); ?>">
                <label for="gender"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.GENDER'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" id="gender" name="gender">
                        <option value="male" <?php if ('male' == $applicantInput->getGender()) echo ' selected'; ?>>
                            <?php echo $hornlocalizer->i18n('FORM.GENDER.M'); ?>
                        </option>
                        <option value="female" <?php if ('female' == $applicantInput->getGender()) echo ' selected'; ?>>
                            <?php echo $hornlocalizer->i18n('FORM.GENDER.F'); ?>
                        </option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('gender'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('vorname'); ?>">
                <label for="vorname"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.FIRSTNAME'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="vorname" id="vorname"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.FIRSTNAME.PL'); ?>"
                           value="<?php echo $applicantInput->getFirstname(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('vorname'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('nachname'); ?>">
                <label for="nachname"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.LASTNAME'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nachname" id="nachname"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.LASTNAME.PL'); ?>"
                           value="<?php echo $applicantInput->getLastname(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('nachname'); ?>
                </div>
            </div>

            <p><?php echo $hornlocalizer->i18n('FORM.ADDRESS'); ?></p>

            <div class="form-group <?php echo $applicantInput->getUIResponse('street'); ?>">
                <label for="street"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.STREET'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="street" id="street"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.STREET.PL'); ?>"
                           value="<?php echo $applicantInput->getStreet(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('street'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('houseno'); ?>">
                <label for="houseno"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.HOUSENO'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="houseno" name="houseno"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.HOUSENO.PL'); ?>"
                           value="<?php echo $applicantInput->getHouseNumber(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('houseno'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('plz'); ?>">
                <label for="plz" class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.ZIP'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="plz" id="plz"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.ZIP.PL'); ?>"
                           value="<?php echo $applicantInput->getZipCode(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('plz'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('city'); ?>">
                <label for="city"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.CITY'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="city" id="city"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.CITY.PL'); ?>"
                           value="<?php echo $applicantInput->getCity(); ?>"/>
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
                <label for="country"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.COUNTRY'); ?></label>
                <div id="country" data-name="country" class="col-sm-10 bfh-selectbox bfh-countries"
                     data-country="<?php echo $applicantInput->getCountry(); ?>" data-flags="true">
                    <?php echo $applicantInput->showSymbolIfFeedback('country'); ?>
                </div>
            </div>

            <p><?php echo $hornlocalizer->i18n('FORM.EMAIL.LABEL'); ?></p>
            <div class="form-group <?php echo $applicantInput->getUIResponse('email'); ?>">
                <label for="email"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.EMAIL'); ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.EMAIL.PL'); ?>"
                           value="<?php echo $applicantInput->getEmail(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('email'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('emailcheck'); ?>">
                <label for="emailcheck"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.EMAILCHECK'); ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="emailcheck" id="emailcheck"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.EMAILCHECK.PL'); ?>"
                           value="<?php echo $applicantInput->getEmailcheck(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('emailcheck'); ?>
                </div>
            </div>

            <legend><?php echo $hornlocalizer->i18n('FORM.AIKIDO'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('dojo'); ?>">
                <label for="dojo"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.DOJO'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dojo" id="dojo"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.DOJO.PL'); ?>"
                           value="<?php echo $applicantInput->getDojo(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('dojo'); ?>
                </div>
            </div>

            <p><?php echo $hornlocalizer->i18n('FORM.TWA.LABEL'); ?></p>
            <div class="form-group <?php echo $applicantInput->getUIResponse('twano'); ?>">
                <label for="twano"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.TWA'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="twano" id="twano"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.TWA.PL'); ?>"
                           value="<?php echo $applicantInput->getTwaNumber(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('twano'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('grad'); ?>">
                <label for="grad"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.GRADING'); ?></label>
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

            <p><?php echo $hornlocalizer->i18n('FORM.GSINCE.CHANGE'); ?></p>
            <div class="form-group <?php echo $applicantInput->getUIResponse('gsince'); ?>">
                <label for="gsince"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.GSINCE'); ?></label>
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

            <legend><?php echo $hornlocalizer->i18n('FORM.ROOM.PL'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('room'); ?>">
                <label class="col-sm-2 control-label"
                       for="room"><?php echo $hornlocalizer->i18n('FORM.ROOM'); ?></label>
                <div class="col-sm-10">
                    <select class="form-control" name="room" id="room">
                        <option value="3bed" <?php if ('3bed' == $applicantInput->getRoom()) echo ' selected'; ?>><?php echo $hornlocalizer->i18n('FORM.ROOM.3'); ?></option>
                        <option value="2bed" <?php if ('2bed' == $applicantInput->getRoom()) echo ' selected'; ?>><?php echo $hornlocalizer->i18n('FORM.ROOM.2'); ?></option>
                        <option value="1bed" <?php if ('1bed' == $applicantInput->getRoom()) echo ' selected'; ?>><?php echo $hornlocalizer->i18n('FORM.ROOM.1'); ?></option>
                    </select>
                    <?php echo $applicantInput->showSymbolIfFeedback('room'); ?>
                </div>
            </div>

            <div id="together1-group">
                <p><?php echo $hornlocalizer->i18n('FORM.PARTNER'); ?></p>
                <div class="form-group <?php echo $applicantInput->getUIResponse('together1'); ?>">
                    <label for="together1"
                           class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.PARTNER.P1'); ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="together1" id="together1"
                               placeholder="<?php echo $hornlocalizer->i18n('FORM.PARTNER.PL'); ?>"
                               value="<?php echo $applicantInput->getPartnerOne(); ?>"/>
                        <?php echo $applicantInput->showSymbolIfFeedback('together1'); ?>
                    </div>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('together2'); ?>" id="together2-group">
                <label for="together2"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.PARTNER.P2'); ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="together2" id="together2"
                           placeholder="<?php echo $hornlocalizer->i18n('FORM.PARTNER.PL'); ?>"
                           value="<?php echo $applicantInput->getPartnerTwo(); ?>"/>
                    <?php echo $applicantInput->showSymbolIfFeedback('together2'); ?>
                </div>
            </div>

            <div class="form-group <?php echo $applicantInput->getUIResponse('essen'); ?>">
                <label class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.FOOD'); ?></label>
                <div class="col-sm-10">
                    <div class="radio" id="essen">
                        <label>
                            <input type="radio" name="essen" id="meat"
                                   value="meat" <?php if ('meat' == $applicantInput->getFoodCategory()) echo ' checked'; ?>>
                            <?php echo $hornlocalizer->i18n('FORM.FOOD.MEAT'); ?>
                        </label>
                        <label>
                            <input type="radio" name="essen" id="veg"
                                   value="veg" <?php if ('veg' == $applicantInput->getFoodCategory()) echo ' checked'; ?>>
                            <?php echo $hornlocalizer->i18n('FORM.FOOD.VEG'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <legend><?php echo $hornlocalizer->i18n('FORM.OTHER'); ?></legend>
            <div class="form-group <?php echo $applicantInput->getUIResponse('additionals'); ?>">
                <label for="additionals"
                       class="col-sm-2 control-label"><?php echo $hornlocalizer->i18n('FORM.REMARK'); ?></label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="additionals" id="additionals" rows="13" maxlength="400"
                              placeholder="<?php echo $hornlocalizer->i18n('FORM.REMARK.PL'); ?>"><?php echo $applicantInput->getRemarks(); ?></textarea>
                    <?php echo $applicantInput->showSymbolIfFeedback('additionals'); ?>
                </div>
            </div>

            <?php if ($applicantInput->showFormButtons()) { ?>
            <p class="lead"><?php echo $hornlocalizer->i18n('FORM.MANDATORYFIELDS') ?></p>
            <div class="form-group">
                <button type="submit" class="btn btn-default btn-primary"
                        title="<?php echo $hornlocalizer->i18n('FORM.SUBMIT'); ?>"><?php echo $hornlocalizer->i18n('FORM.SUBMIT'); ?></button>
                <button type="reset" class="btn btn-danger"
                        title="<?php echo $hornlocalizer->i18n('FORM.RESET'); ?>"><?php echo $hornlocalizer->i18n('FORM.RESET'); ?></button>
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

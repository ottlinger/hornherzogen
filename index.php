<!DOCTYPE html>
<?php require 'vendor/autoload.php';
use hornherzogen\HornLocalizer;
use hornherzogen\ConfigurationWrapper;

$config = new ConfigurationWrapper();
$hornlocalizer = new HornLocalizer();
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

    <title><?php echo $hornlocalizer->i18n('INDEX.TITLE'); ?></title>

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
                <li><a href="./form.php"><span class="glyphicon glyphicon-home"></span> <?php echo $hornlocalizer->i18n('MENU.APPLY'); ?></a></li>
                <li><a href="./contact.php"><span class="glyphicon glyphicon-envelope"></span> <?php echo $hornlocalizer->i18n('MENU.FAQ'); ?></a></li>
                <li><a href="./admin"><span class="glyphicon glyphicon-briefcase"></span> <?php echo $hornlocalizer->i18n('MENU.ADMIN'); ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-education"></span> <?php echo date('Y-m-d H:i:s'); ?></a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    <div class="starter-template">
      <a href="https://github.com/ottlinger/hornherzogen" target="_blank"><img style="position: absolute; top: 100px; right: 0; border: 0;" src="https://camo.githubusercontent.com/e7bbb0521b397edbd5fe43e7f760759336b5e05f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677265656e5f3030373230302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png"></a>
        <h1><span class="glyphicon glyphicon-dashboard"></span> <?php echo $hornlocalizer->i18n('INDEX.WELCOME'); ?></h1>
        <p><?php echo $hornlocalizer->i18n('INDEX.LINE1'); ?><br/>
            <?php echo $hornlocalizer->i18n('INDEX.LINE2'); ?><br/><br/>
            <?php echo $hornlocalizer->i18nParams('INDEX.TENDERLINK', $config->pdf()); ?>
        </p>

<!-- if russian is available add flags
        <p>
            <span class="bfh-languages" data-language="" data-flags="true">
                <i class="glyphicon bfh-flag-DE"></i>
            </span>
            <span class="bfh-languages" data-language="" data-flags="true">
                <i class="glyphicon bfh-flag-RU"></i>
            </span>
            <span class="bfh-languages" data-language="" data-flags="true">
                <i class="glyphicon bfh-flag-GB"></i>
            </span>
        </p>
-->
        <p class="lead"><a href="form.php?lang=en"><span class="glyphicon glyphicon-hand-right"></span> <?php echo $hornlocalizer->i18n('INDEX.EN_CONTINUE'); ?></a></p>
        <p class="lead"><a href="form.php?lang=de"><span class="glyphicon glyphicon-hand-right"></span> <?php echo $hornlocalizer->i18n('INDEX.DE_CONTINUE'); ?></a></p>
    </div><!-- /.starter-template -->
</div><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="./js/bootstrap.min.js"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="./js/bootstrap-formhelpers.min.js"></script>
</body>
</html>

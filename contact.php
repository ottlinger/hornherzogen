<!DOCTYPE html>
<?php require 'vendor/autoload.php'; ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Herzogenhorn 2017 Anmeldung">
    <meta name="author" content="OTG">
    <meta name="robots" content="none,noarchive,nosnippet,noimageindex"/>
    <link rel="icon" href="./favicon.ico">

    <title>Herzogenhorn 2017 - Fragen / Kontakt</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="./css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/starter-template.css" rel="stylesheet">
    <link href="./css/theme.css" rel="stylesheet">

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
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./index.php"><span class="glyphicon glyphicon-tree-conifer"></span>
                Herzogenhorn 2017</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="./form.php"><span class="glyphicon glyphicon-home"></span> Anmeldung</a></li>
                <li class="active"><a href="./contact.php"><span class="glyphicon glyphicon-envelope"></span> Fragen</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><span class="glyphicon glyphicon-education"></span><?php echo date('Y-m-d H:i:s'); ?></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    <div class="starter-template">
      <a href="https://github.com/ottlinger/hornherzogen" target="_blank"><img style="position: absolute; top: 100px; right: 0; border: 0;" src="https://camo.githubusercontent.com/e7bbb0521b397edbd5fe43e7f760759336b5e05f/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f677265656e5f3030373230302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png"></a>
        <h1><span class="glyphicon glyphicon-send"></span> Anmeldung</h1>
        <p class="lead">Die Ausschreibung gibt es seit 2017-02-xx,<br>bitte für die jeweilige Lehrgangswoche <a
                    href="form.php">elektronisch</a> anmelden und per E-Mail bestätigen.</p>
    </div>

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-paperclip"></span> Buchungsbestätigung</h1>
        <p class="lead">Sobald die Ausschreibungsfrist zum 2017-03-xx endet,<br>senden wir die Anmeldebestätigungen mit
            Zahlungsaufforderung zu.</p>
    </div>

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-usd"></span> Zahlungseingang</h1>
        <p class="lead">Sobald die Zahlung der Lehrgangsgebühr eingegangen ist,<br>übersenden wir eine Rechnung und die
            finale Teilnahmebestätigung.</p>
    </div>

    <div class="starter-template">
        <h1>Noch Fragen? Lob oder Kritik?</h1>
        <p class="lead">Um Benjamin oder Philipp zu kontaktieren<br>am einfachsten eine <a href="mailto:<?php echo $GLOBALS["horncfg"]["mail"];?>">Mail</a> schreiben.</p>
        <p>Today is <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="./js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

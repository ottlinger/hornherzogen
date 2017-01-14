<!DOCTYPE html>
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

    <title>Herzogenhorn Anmeldeformular - abgeschickt</title>

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
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./index.php"><span class="glyphicon glyphicon-tree-conifer"></span> Herzogenhorn 2017</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="./form.php"><span class="glyphicon glyphicon-home"></span> Anmeldung</a></li>
            <li><a href="./contact.php"><span class="glyphicon glyphicon-envelope"></span> Fragen</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container theme-showcase">
      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-sunglasses"></span> Herzogenhorn 2017</h1>
        <p class="lead">Danke für die Ammeldung<br />bitte die Bestätigungsmail abwarten.</p>
	      <p>Today is <?php echo date('Y-m-d H:i:s');?></p>

          <?php

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
              echo $_POST["week"];
              echo $_POST["grad"];
              echo $_POST["week"];
          } else {
              echo "Nothing to be done";
          }

          ?>
      </div>

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

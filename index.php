<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

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
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
	<p>Today is <?php echo date('Y-m-d H:i:s');?>

    <form class="form-horizontal">
            <fieldset>
              <legend>Lehrgangsauswahl</legend>
              <div class="control-group">
                <label class="control-label" for="week">Welche Woche?</label>
                <div class="controls">
                  <select id="week">
                    <option value="horn2012w1">1.Woche - ab Sa, 2012-06-17</option>
                    <option value="horn2012w2">2.Woche - ab Sa, 2012-06-24</option>
                  </select>
                  <p class="help-block">Bitte die gewünschte Lehrgangswoche festlegen.</p>
                </div>
    			 </div>
    			</fieldset>

            <fieldset>
              <legend>Persönliche Daten</legend>
    <!-- class="error" hinzufügen bei Fehlern -->
              <div class="control-group">
                <?php /* via JS username will be printed in the login block */ ?>
                <label class="control-label" for="vorname">Vorname</label>
                <div class="controls ">
                  <input type="text" class="input-xlarge" id="vorname">
                  <p class="help-block">Bitte Vornamen eingeben.</p>
                </div>
    			 </div>
              <div class="control-group">
                <label class="control-label" for="nachname">Name</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="nachname">
                  <p class="help-block">Bitte Name eingeben.</p>
                </div>
    			 </div>
              <div class="control-group">
                <label class="control-label" for="street">Straße</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="street">
                  <p class="help-block">Bitte Straße eingeben.</p>
                </div>
    			 </div>
              <div class="control-group">
                <label class="control-label" for="houseno">Nr.</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="houseno">
                  <p class="help-block">Bitte Hausnummer komplett eingeben.</p>
                </div>
    			 </div>
              <div class="control-group">
                <label class="control-label" for="plz">PLZ</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="plz">
                  <p class="help-block">Bitte Postleitzahl eingeben.</p>
                </div>
    			 </div>
              <div class="control-group">
                <label class="control-label" for="loc">Ort</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="loc">
                  <p class="help-block">Bitte Ort eingeben.</p>
                </div>
    			 </div>
              <div class="control-group">
                <label class="control-label" for="email">E-Mail</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="email">
                  <p class="help-block">Bitte E-Mail-Adresse für Bestätigungen eingeben.</p>
                </div>
    			 </div>
    <!-- TODO vergangenheit auswählen -->
              <div class="control-group">
                <label class="control-label" for="datepicker">Geburtsdatum Test mit falschem Picker</label>
                <div class="controls">
                  <input type="text" id="datepicker" size="30"/>
                  <p class="help-block">Bitte Geburtsdatum auswählen.</p>
                </div>

              <div class="control-group">
                <label class="control-label" for="g_day">Geburtsdatum</label>
                <div class="controls">
                  <select id="g_day">
                    <option value="">Tag</option>
                    <?php
    for ($i = 1; $i <= 31; $i++) {
        echo "<option>".$i."</option>";
    }
    ?>
                  </select>
                  <select id="g_month">
                    <option value="">Monat</option>
                    <?php
                    /** TODO add i18n here
                    // prints something like: 2000-07-01T00:00:00+00:00
                    echo date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
                    */
                    date_default_timezone_set('UTC');
    for ($i = 1; $i <= 12; $i++) {
        echo "<option>".date("F", mktime(0, 0, 0, $i, 1, 2012))."</option>";
    }
    ?>
                  </select>
                  <input type="text" class="input-xlarge" id="g_year">
                  <p class="help-block">Bitte das Geburtsjahr 4stellig eingeben.</p>
                </div>
    			 </div>

              <div class="control-group">
                <label class="control-label" for="grad">Graduierung</label>
                <div class="controls">
                  <select id="grad">
                    <option>1.Kyu</option>
                    <option>1.Dan</option>
                  </select>
                  <p class="help-block">Bitte die aktuelle Graduierung auswählen.</p>
                </div>
                <label class="control-label" for="gsince_month">Graduiert seit</label>
                <div class="controls">
                  <select id="gsince_month">
                    <option value="">Monat</option>
                    <?php
                    /** TODO add i18n here
                    // prints something like: 2000-07-01T00:00:00+00:00
                    echo date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000));
                    */
                    date_default_timezone_set('UTC');
    for ($i = 1; $i <= 12; $i++) {
        echo "<option>".date("F", mktime(0, 0, 0, $i, 1, 2012))."</option>";
    }
    ?>
                  </select>
                  <input type="text" class="input-xlarge" id="gsince_year">
                  <p class="help-block">Bitte angeben, seit wann die aktuelle Graduierung besteht.</p>
                </div>
    			 </div>

              <div class="control-group">
                <label class="control-label" for="twano">Mitgliedsnummer (twa)</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="twano">
                  <p class="help-block">Bitte die komplette twa-Mitgliedsnummer angeben (z.B. DE-0815) insofern vorhanden.</p>
                </div>
    		 </div>
    		</fieldset>

             <fieldset>
              <legend>Daten zur Unterkunft</legend>
              <div class="control-group">
                <label class="control-label" for="room">Zimmertyp</label>
                <div class="controls">
                  <select id="room">
                    <option >2-Bett Zimmer Dusche und WC</option>
                    <option value="together22" selected>3-Bett Zimmer Dusche und WC</option>
                  </select>
                  <p class="help-block">Bitte die Zimmerkategorie festlegen.</p>
                </div>
    			 </div>


              <div class="control-group">
                <label class="control-label" for="together1">Zusammenlegungswunsch</label>
                <div class="controls">
                  <input type="text" class="input-xlarge" id="together1">
                  <p class="help-block">Mit wem soll das Zimmer geteilt werden - Person 1</p>
                </div>
                <div id="together22" class="controls">
                  <input type="text" class="input-xlarge" id="together2">
                  <p class="help-block">Mit wem soll das Zimmer geteilt werden - Person 2</p>
                </div>
    			 </div>

              <div class="control-group">
                <label class="control-label">Essenswunsch</label>
                <div class="controls">
                  <label class="radio">
                    <input type="radio" name="essen" id="meat" value="meat" checked>
                    normale Kost (mit Fleisch)
                  </label>
                  <label class="radio">
                    <input type="radio" name="essen" id="veg" value="veg">
                    vegetarische Kost (ohne Fleisch)
                  </label>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label">Kann ich im Fall einer Überbuchung in die andere Woche ausweichen?</label>
                <div class="controls">
                  <label class="radio">
                    <input type="radio" name="flexible" id="no" value="no" checked>
                    Ich kann nur in dieser Woche am Lehrgang teilnehmen.
                  </label>
                  <label class="radio">
                    <input type="radio" name="flexible" id="yes" value="yes">
                    Ich bin flexibel, <strong>falls</strong> diese Woche überbucht ist.
                  </label>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label" for="additionals">Bemerkungen / Wünsche</label>
                <div class="controls">
                  <textarea class="input-xlarge" id="additionals" rows="13"></textarea>
                </div>
              </div>
              <div class="form-actions">
               <button type="submit" class="btn btn-primary" title="Anmeldung verbindlich machen" data-content="And here's some amazing content. It's very engaging. right?">Anmeldevorgang einleiten</button>
               <button type="reset" class="btn btn-danger" >Alle Eingaben löschen</button>
              </div>
            </fieldset>
          </form>






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

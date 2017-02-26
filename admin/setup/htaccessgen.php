<?php
require '../../vendor/autoload.php';
use hornherzogen\FormHelper;

const PW_USER = 'pwusr';
const PW_PW = 'pwclear';
const DEFAULT_VALUE = 'test';

$formHelper = new FormHelper();
?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <title>Helper to generate new entries in your .htpasswd file</title>
    </head>
<body>

<h1>HTPASSWD entry generator</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for"pwusr">User:
    <input type="text" name="pwusr"
           value="<?php echo $formHelper->isSetAndNotEmptyInArray($_POST, PW_USER) ? $formHelper->filterUserInput($_POST[PW_USER]) : ''; ?>">
    <br><br>
    <label for"pwclear">Password:
    <input type="text" name="pwclear"
           value="<?php echo $formHelper->isSetAndNotEmptyInArray($_POST, PW_PW) ? $formHelper->filterUserInput($_POST[PW_PW]) : ''; ?>">
    <br><br>
    <input type="submit" name="submit" value="Submit" autofocus>
</form>

<hr/>
<?php
// safely extract data and generate entry for .htpasswd file
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $clearTextPassword = $formHelper->isSetAndNotEmptyInArray($_POST, PW_PW) ? $formHelper->filterUserInput($_POST[PW_PW]) : DEFAULT_VALUE;
    $user = $formHelper->isSetAndNotEmptyInArray($_POST, PW_USER) ? $formHelper->filterUserInput($_POST[PW_USER]) : DEFAULT_VALUE;

    $password = crypt($clearTextPassword, base64_encode($clearTextPassword));

    echo "<h2>You may add this line to your .htpasswd</h2>";
    echo "<pre>";
    echo "## User generated with " . htmlspecialchars($_SERVER["PHP_SELF"]) . " at " . $formHelper->timestamp() . "\n";
    echo $user . ":" . $password . "\n";
    echo "</pre>";
} else {
    echo "Please submit the form to see how new users can be added to your htpasswd installation.";
}
?>
<hr/>


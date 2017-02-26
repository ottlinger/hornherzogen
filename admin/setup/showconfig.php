<?php

require '../../vendor/autoload.php';

use \hornherzogen\ConfigurationWrapper;

echo "<h2>What's the current configuration?</h2>";
echo new ConfigurationWrapper();
<?php

require '../../vendor/autoload.php';

use hornherzogen\admin\BankingConfiguration;
use hornherzogen\ConfigurationWrapper;

echo "<h2>What's the current configuration?</h2>";
echo new ConfigurationWrapper();

echo "<h2>What's the account configuration?</h2>";
echo new BankingConfiguration();

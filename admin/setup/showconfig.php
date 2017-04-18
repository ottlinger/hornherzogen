<?php

require '../../vendor/autoload.php';

use hornherzogen\ConfigurationWrapper;
use hornherzogen\admin\BankingConfiguration;

echo "<h2>What's the current configuration?</h2>";
echo new ConfigurationWrapper();

echo "<h2>What's the account configuration?</h2>";
echo new BankingConfiguration();

<?php
namespace hornherzogen;

class AdminHelper
{
    private $config;

    public function __construct()
    {
        $this->config = new ConfigurationWrapper;
    }

    public function isAdmin() {
       return false;
    }

    public function getUserName() {

    }


}
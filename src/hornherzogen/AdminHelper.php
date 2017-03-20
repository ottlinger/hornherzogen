<?php
namespace hornherzogen;

class AdminHelper
{
    private $config;

    public function __construct()
    {
        $this->config = new ConfigurationWrapper;
    }

    public function isAdmin()
    {
        if (NULL != $this->config->superuser() && in_array($this->getUserName(), $this->config->superuser())) {
            return true;
        }

        return false;
    }

    public function getUserName()
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            echo trim($_SERVER['PHP_AUTH_USER']);
        }
        return "none";
    }


}
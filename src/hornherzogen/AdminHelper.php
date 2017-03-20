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
        if (NULL != $this->config->superuser() && strpos($this->getUserName(), $this->config->superuser()) !== FALSE) {
            return true;
        }
        return false;
    }

    public function getUserName()
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return trim($_SERVER['PHP_AUTH_USER']);
        }
        return "none";
    }

    public function showUserLoggedIn() {
        $user = $this->getUserName();

        if($this->isAdmin()) {
            return '<span class="glyphicon glyphicon-user style: \'color: red;\'"></span> '.$user.'</a>';
        }
        return '<span class="glyphicon glyphicon-user"></span> '.$user.'</a>';
    }


}
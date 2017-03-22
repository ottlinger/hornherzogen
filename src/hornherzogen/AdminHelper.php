<?php
namespace hornherzogen;

class AdminHelper
{
    private $config;
    const FALLBACK_USER = "none";

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
        return self::FALLBACK_USER;
    }

    public function showUserLoggedIn() {
        $user = $this->getUserName();

        if(boolval($this->isAdmin())) {
            return '<span class="glyphicon glyphicon-user" style="color: red;"></span> '.$user.'</a>';
        }
        return '<span class="glyphicon glyphicon-user"></span> '.$user.'</a>';
    }

    public function showLogoutMenu() {
        if(self::FALLBACK_USER != $this->getUserName()) {
            return '<li><a href="#"><span class="glyphicon glyphicon-erase"></span> Logout</a></li>';
        }
        return '<li><a href="#"><span class="glyphicon glyphicon-lamp"></span> Not logged in</a></li>';
    }

    public function showSuperUserMenu() {
        return "";
    }

}

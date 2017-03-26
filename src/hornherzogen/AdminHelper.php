<?php

namespace hornherzogen;

class AdminHelper
{
    const FALLBACK_USER = "none";
    private $config;
    private $formHelper;

    public function __construct()
    {
        $this->config = new ConfigurationWrapper;
        $this->formHelper = new FormHelper();
    }

    public function showUserLoggedIn()
    {
        $user = $this->getUserName();

        if (boolval($this->isAdmin())) {
            return '<span class="glyphicon glyphicon-user" style="color: red;"></span> ' . $user . '</a>';
        }
        return '<span class="glyphicon glyphicon-user"></span> ' . $user . '</a>';
    }

    public function getUserName()
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return trim($_SERVER['PHP_AUTH_USER']);
        }
        return self::FALLBACK_USER;
    }

    public function isAdmin()
    {
        if (NULL != $this->config->superuser() && strpos($this->getUserName(), $this->config->superuser()) !== FALSE) {
            return true;
        }
        return false;
    }

    public function showLogoutMenu()
    {
        if (self::FALLBACK_USER != $this->getUserName()) {
            return '<li><a href="./logout.php"><span class="glyphicon glyphicon-erase"></span> Logout</a></li>';
        }
        return '<li><a href="#"><span class="glyphicon glyphicon-lamp"></span> Not logged in</a></li>';
    }

    public function showSuperUserMenu()
    {
        if ($this->isAdmin() || $this->getHost() == 'localhost') {
            return "
                <li class=\"dropdown\">
                <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-road\"></span> Superadmin-Menu<span class=\"caret\"></span></a>
                <ul class=\"dropdown-menu\">
                  <li><a href=\"./setup/i18ncheck.php\">L10n-Statistik</a></li>
                  <li><a href=\"./db/db_stats.php\" target=\"_blank\">DB-Tabellen-Statistik</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li><a href=\"./db/db_statuses.php\" target=\"_blank\">Status-Werte</a></li>
                  <li><a href=\"./db/db_bookings.php\" target=\"_blank\">Buchungs-Werte</a></li>
                  <li><a href=\"./db/db_rooms.php\" target=\"_blank\">Räume-Werte</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li class=\"dropdown-header\">KONFIGURATION</li>
                  <li><a href=\"./setup/php.php\" target=\"_blank\">PHP-Versionen und Module</a></li>
                  <li><a href=\"./setup/i18n.php\" target=\"_blank\">Geht i10n?</a></li>
                  <li><a href=\"./db/db_connect.php\" target=\"_blank\">Geht DB-Verbindung?</a></li>
                  <li><a href=\"./setup/showconfig.php\" target=\"_blank\">aktuelle Anwendungskonfiguration</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li class=\"dropdown-header\">ZUGRIFFSSCHUTZ</li>
                  <li><a href=\"./setup/path.php\" target=\"_blank\">Pfad .htaccess</a></li>
                  <li><a href=\"./setup/htaccessgen.php\" target=\"_blank\">.htpasswd - neuer Nutzer</a></li>
                  <li role=\"separator\" class=\"divider\"></li>
                  <li class=\"dropdown-header\">GEFÄHRLICH</li>
                  <li><a href=\"./db/dbtest.php\" target=\"_blank\">Roundtrip - geht nur 1x</a></li>
                </ul>
              </li>
            ";
        }
        return "<li><a href=\"#\"><span class=\"glyphicon glyphicon-road\"></span>No Superadmin-Menu</a></li>";
    }

    public function getHost()
    {
        if ($this->formHelper->isSetAndNotEmptyInArray($_SERVER, 'SERVER_NAME')) {
            return trim($_SERVER['SERVER_NAME']);
        }
        // to avoid DNS manipulation to get superadmin access
        return NULL;
    }

    /**
     * @return string this page's URL with protocol and port.
     */
    function thisPageUrl()
    {
        $pageURL = 'http';
        if ($this->formHelper->isSetAndNotEmptyInArray($_SERVER, "HTTPS") && $_SERVER["HTTPS"] === "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($this->formHelper->isSetAndNotEmptyInArray($_SERVER, "SERVER_PORT") && $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $this->getHost() . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            return $pageURL;
        }

        $pageURL .= $this->getHost() . $_SERVER["REQUEST_URI"];
        return $pageURL;
    }

}

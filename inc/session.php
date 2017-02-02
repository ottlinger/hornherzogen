<?php

/**
 * Initialise session in order to save the chosen language.
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    echo "<pre>SessionStarted</pre>";
} else {
    echo "<pre>NoSessionToStart</pre>";
}

echo "<pre>".var_dump($_SESSION)."////".session_status()."</pre>";
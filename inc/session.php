<?php

/**
 * Initialise session in order to save the chosen language.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

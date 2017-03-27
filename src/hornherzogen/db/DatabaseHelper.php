<?php
declare(strict_types=1);

namespace hornherzogen\db;

class DatabaseHelper
{
    /**
     * Trims given data and surrounds with quotes for SQL insertion.
     * @param $input
     * @return null|string
     */
    public function trimAndMask($input)
    {
        $trimmed = $this->emptyToNull($input);
        if (boolval($trimmed)) {
            return '\'' . $trimmed . '\'';
        }
        return NULL;
    }

    /**
     * A given empty String is converted to a NULL value.
     * @param $input
     * @return null|string
     */
    public function emptyToNull($input)
    {
        if (isset($input) && strlen(trim('' . $input))) {
            return trim('' . $input);
        }
        return NULL;
    }

    /**
     * Replaces the given String to make it compliant with a SQL-statement, thus all percentages and underscores are properly escaped and the String is quoted.
     * @param $input input String to handle
     * @param $database optional given PDO/database connection that can be used to properly quote it, if not provided simple quotes will be added in the beginning and at the end.
     * @return string
     */
    public function makeSQLCapable($input, $database)
    {
        if (isset($input)) {
            if (isset($database)) {
                $mask = $database->quote($input);
            } else {
                $mask = "'" . $input . "'";
            }
            $mask = strtr($mask, array('_' => '\_', '%' => '\%'));
            return $mask;
        }
        return $input;
    }

}


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

    public function emptyToNull($input)
    {
        if (isset($input) && strlen(trim('' . $input))) {
            return trim('' . $input);
        }
        return NULL;
    }
}


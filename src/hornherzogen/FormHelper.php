<?php
namespace hornherzogen;

class FormHelper
{
    public function filterUserInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function trimAndCutAfter($input, $length)
    {
        if (isset($input) && isset($length)) {
            return mb_substr(trim($input), 0, $length, 'UTF-8');
        }
    }

    public function timestamp() {
        return date('Y-m-d H:i:s');
    }


}
<?php
namespace hornherzogen;

class FormHelper
{
    public function filterUserInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

}
<?php
    require 'Validatori/HtmlValidator.php';
    if (isset ($_REQUEST['validacija']))
    {
        $v = $_REQUEST['validacija'];
        $sta = $_REQUEST['sta'];
        if ($v === "w3c")
            echo ValidirajW3C ($sta);
        else if ($v === "bla")
            $s = 2;
    }
function ValidirajW3C ($sta)
{
    $val = new \W3C\HtmlValidator();
    return $val->validateInput($sta);
}
?>
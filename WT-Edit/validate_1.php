<?php
    require 'Validatori' . DIRECTORY_SEPARATOR . 'HtmlValidator.php';
    if (isset ($_REQUEST['validacija']))
    {
        $v = $_REQUEST['validacija'];
        $sta = $_REQUEST['sta'];
        if ($v === "w3c") {

            $s = ValidirajW3C($sta);
            if ($s->isValid())
            {
                echo "<h1>Validan!</h1>";
            }
            else
            {
                echo "<h1>NIJE Validan! Greske:</h1><ul>";
                foreach ($s->getErrors() as $r)
                    echo "<li>" . $r->getMessage() . "</li>";
                echo "</ul>";
            }
        }
#TODO: nu validator
        else if ($v === "bla")
            $s = 2;
    }
function ValidirajW3C ($sta)
{
    $val = new \HtmlValidator();
    $a = $val->validateInput($sta);
    #var_dump($a);
    echo "<hr>";
    #var_dump($val);
    return $a;
}
?>
<?php
    require 'Validatori' . DIRECTORY_SEPARATOR . 'HtmlValidator.php';
    #potrebno je insalirati: HTTP, Net/Request2, W3C Service CSS Validator...
    require_once 'Services/W3C/CSSValidator.php';
    if (isset ($_REQUEST['validacija']))
    {
        $v = $_REQUEST['validacija'];
        $sta = $_REQUEST['sta'];
        if ($v === "html") {
            $validan = "ne";
            $u = array();
            $g = array();
            $s = ValidirajW3C($sta);
            if ($s->isValid())
                $validan = "da";
            else
            {
                foreach ($s->getErrors() as $r)
                {
                    $w = "<b>" . $r->getMessage() . "</b>";
                    $w .= "<br>Linija: " . intval($r->getLine());
                    $w .= "<br>Kolona: " . intval($r->getColumn());
                    $g[] = $w;
                }
                foreach ($s->getWarnings() as $r)
                {
                    $w = $r->getMessage();
                    if (substr($w, 0, 14) === "This interface")
                        continue; # da preskočimo one dosadne poruke
                    $w = "<b>" . $r->getMessage() . "</b>";
                    $w .= "<br>Linija: " . intval($r->getLine());
                    $w .= "<br>Kolona: " . intval($r->getColumn());
                    $u[] = $w;
                }
            }
            echo "{ \"status\": \"OK\", \"validan\": \"$validan\", \"greske\":" . json_encode($g) . ", \"upozorenja\":" . json_encode($u) . "}";

        }
        else if ($v === "css")
        {
            $v = new Services_W3C_CSSValidator();
            $r = $v->validateFragment($sta);
            $validan = "ne";
            $u = array();
            $g = array();;
            if ($r->isValid())
                $validan = "da";
            else
            {
                foreach ($r->errors as $q)
                {
                    $w = "<b>" . $q->message . "</b>";
                    $w .= "<br>Linija: " . intval($q->line);
                    $w .= "<br>Kontekst: " . $q->context;
                    $w .= "<br>Property: " . $q->property;
                    $g[] = $w;
                }
                foreach ($r->warnings as $q)
                {
                    $w = "<b>" . $q->message . "</b>";
                    $w .= "<br>Linija: " . intval($q->line);
                    $g[] = $w;
                }
            }
            echo "{ \"status\": \"OK\", \"validan\": \"$validan\", \"greske\":" . json_encode($g) . ", \"upozorenja\":" . json_encode($u) . "}";
        }
    }
function ValidirajW3C ($sta)
{
    $val = new \HtmlValidator();
    $a = $val->validateInput($sta);
    #var_dump($a);
    #var_dump($val);
    return $a;
}
?>
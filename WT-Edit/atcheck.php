<?php
require_once ('../general.php');
if (isset ($_POST['atid']) && isset ($_POST['result']))
{
    $b = InitBase();
    $ID = intval(XSS ($_POST['atid']));
    $u = $b->prepare("SELECT ID, REZULTAT FROM at_autotestovi WHERE ID = :id");
    $u->bindValue(":id", $ID, PDO::PARAM_INT);
    $u->execute();
    $r = $u->fetch();
    if (!$u)
        ProcessError($b);
    if (!$r)
    {
        echo "-1"; #AT sa ID-om ne postoji!
        exit();
    }
    $cs = boolval(['CASE_SENSITIVE']);
    $re = CaseS ($_POST['result'], $cs);
    if ($re === CaseS ($r['REZULTAT'], $cs))
    {
        echo "1"; #OK poklapanje
        exit();
    }
    if ($re === CaseS ($r['ALT_REZULTAT1'], $cs))
    {
        echo "2"; #Alt 1 poklapanje
        exit();
    }
    if ($re === CaseS ($r['ALT_REZULTAT2'], $cs))
    {
        echo "3"; #Alt 2 poklapanje
        exit();
    }
    if ($re === CaseS ($r['ALT_REZULTAT3'], $cs))
    {
        echo "4"; #Alt 3 poklapanje
        exit();
    }
    echo "0"; #nema poklapanja, AT "pao"
    #TODO: možda **nekako** popraviti pa da ima više varijanti "pao" (netačan rezultat, nema funkcija...)
}
function CaseS ($str, $bool) {return $bool ? $str : strtolower($str);}
?>
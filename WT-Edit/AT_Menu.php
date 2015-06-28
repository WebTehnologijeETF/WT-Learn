<?php
include_once ('../general.php');
include_once ('Autotest.php');
$m = KreirajMenu(date ("Y"));
$aa = <<<T
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editor_menu.css">
    <title></title>
</head>
<body>
<div class='tut-menu'>
    <ul class="mmenu">
    <li>Pokreni Autotest
        $m
    </li>
</ul>
    </div>
</body>
</html>
T;
echo $m;
function KreirajMenu ($y) #ak godina
{
    $T = DajATove($y);
    $R = "<ul class='main-menu'>";
    $nl = PHP_EOL;
    foreach ($T as $TUT)
    {
        $ii = $TUT->BrojZadataka();
        $R .= "<li" . ($ii === 0 ? " class='leaf'" : '') . ">{$TUT->Naziv()}$nl<ul>$nl";
        foreach ($TUT->Zadaci() as $ZAD)
        {
            $i = $ZAD->BrojATova();
            $R .= "<li" . ($i === 0 ? " class='leaf'" : '') . ">{$ZAD->Naziv()}$nl<ul>$nl";
            if ($i > 1) $R .= "<li class='leaf'>$nl<a href='#' onclick='return AutotestirajZadatak ({$ZAD->GetID()});'>Sve autotestove [$i]</a>$nl</li>";
            if ($i === 0)
                goto PRESKOCI;
            foreach ($ZAD->ATovi() as $AT) {
                $s = addslashes($AT->GetCode());
                $R .= "<li class='leaf' title='$s'><a href='#' onclick='return Autotestiraj ({$AT->GetID()})'>" . $AT->GetTitle() . "</a></li>";
            }
            PRESKOCI:
            $R .= "</ul>$nl</li>$nl";
        }
        $R .= "</ul>$nl</li>$nl";

    }
    $R .= "</ul>$nl";
    return $R;
}
function AToviJSON ($y = -1)
{
    if ($y === -1) $y = date ("Y");
    return "{ \"ATOVI\": " . json_encode(DajATove($y)) . "}";
}
function DajATove($y)
{
    $b = InitBase();
    $u = $b->prepare("SELECT ID, NAZIV, AK_GODINA FROM at_tutorijali WHERE AK_GODINA = :ag");
    $u->bindValue(":ag", intval($y), PDO::PARAM_INT);
    $u->execute();
    $r = $u->fetchAll();
    $TUTOVI = array(); #3D niz, sadrži sve tutove, svaki tut sadrži zadatke a svaki zadatak sadrži AT
    if (!$r)
        ProcessError($b);
    foreach ($r as $p)
    {
        $zad = $b->query("SELECT ID, NAZIV, TUTORIJAL FROM at_zadaci WHERE TUTORIJAL = {$p['ID']}");
        if (!$zad)
            ProcessError($b);
        $ETUT = new Tutorijal($p['ID'], $p['NAZIV'], $y);
        foreach ($zad as $at)
        {
            $atovi = $b->query("SELECT ID, NAZIV, KOMENTAR, CODE, REZULTAT, ZADATAK FROM at_autotestovi WHERE ZADATAK = {$at['ID']}");
            if (!$atovi)
                ProcessError($b);
            $EZAD = new Zadatak($at['ID'], $at['NAZIV'], $ETUT->GetID());
            foreach ($atovi as $AT)
            {
                $EZAD->DodajAT(new Autotest ($AT['ID'], #ID autotesta
                    $at['ID'], #ID zadatka
                    $p['ID'],  #ID tutorijala
                    $p['NAZIV'], #naziv tutorijala ex. Tutorijal 1 (HTML)
                    $at['NAZIV'], #naziv zadatka ex. Zadatak 1
                    $AT['NAZIV'], #naziv autotesta, ex "AT 1" ili samo "1"
                    $AT['CODE'], $AT['KOMENTAR'], $AT['REZULTAT']));
            }
            $ETUT->DodajZadatak($EZAD);
        }
        $TUTOVI[] = $ETUT; #dodajemo sve zadatke za jedan tut
    }
    return $TUTOVI;
}
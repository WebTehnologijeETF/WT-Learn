<!DOCTYPE html>
<html>
<head lang="en">
    <link rel="stylesheet" href="main.css">
    <meta charset="UTF-8">
    <title>Novosti | WT Learn</title>
</head>
<body class="l-novosti">
<div id="frame">
    <div id="cont">
        <div id="novosti" class="<?php if (isset ($_REQUEST['vijest'])) echo "no-scroll"; else echo""; ?>">
            <?php
            require_once ("NovostPHP.php");
            require_once ("general.php");
            header('Content-Type: text/html; charset=UTF-8');
            function SveNovosti()
            {
                $baza = InitBase();
                $u = $baza->query("SELECT ID, AUTOR, UNIX_TIMESTAMP(DATUM) AS 'DATUM', K_TEXT, D_TEXT, NASLOV, SLIKA FROM NOVOSTI ORDER BY DATUM DESC");

                if (!$u)
                    ProcessError($baza);
                $novosti = array();
                $i = 0;
                #Fajl *MORA* biti UTF-8 bez BOM
                foreach ($u as $f)
                    $novosti[$i++] = new NovostSQL($f);
#                usort($novosti, array('NovostPHP', 'KriterijSortiranjaUNIX'));

                $html = <<<ETEXT
            <div class="novost">
                <h3 class="h">###NASLOV###<span class="autor">###AUTOR###</span><span class="datum">###DATUM###</span>
                </h3>
                <img class="slika-novost ###IMA_SLIKA###" src="###SLIKA###">
                <div class="data">###KRATKI###</div>
                <div class="clr"></div>
                <div class="lnk ###IMA_VISE###">
                    <a href="#" onclick="UcitajVijestSQL ('###FAJL###');">Više detalja</a>
                </div>
            </div>
ETEXT;
                #imam jedan text da ne bih u petlji ovo radio za $html
                /** @var $e */
                foreach ($novosti as $e) {
                    $copy = str_replace("###DATUM###", $e->DatumString(), $html);
                    $copy = str_replace("###AUTOR###", $e->Autor(), $copy);
                    $copy = str_replace("###IMA_SLIKA###", $e->ImaSlika() ? "slika-aktivna" : "slika-neaktivna", $copy);
                    $copy = str_replace("###SLIKA###", $e->LinkSlike(), $copy);
                    $copy = str_replace("###KRATKI###", $e->KratkiTekst(), $copy);
                    $copy = str_replace("###NASLOV###", $e->Naslov(), $copy);
                    $copy = str_replace("###FAJL###", $e->ID(), $copy);
                    $copy = str_replace("###IMA_VISE###", $e->ImaDetaljnijegTeksta() ? "" : "invisible", $copy);
                    echo $copy;
                }
            }
            function JednNovost($s)
            {

                $s = intval($s);
                $baza = InitBase();
                $u = $baza->prepare("SELECT ID, AUTOR, UNIX_TIMESTAMP(DATUM) AS 'DATUM', K_TEXT, D_TEXT, NASLOV, SLIKA FROM NOVOSTI WHERE ID = :id ORDER BY DATUM DESC");
                $u->bindValue(":id", $s, PDO::PARAM_INT);
                $u->execute();
                if (!$u)
                    ProcessError($baza);
                $a = $u->fetch();
                if (!$a)
                {
                    echo "<h2>Novost sa datim ID-om ne postoji!</h2>";
                    exit();
                }
                $nov = new NovostSQL($a);
                echo "<h1 class='novost-h1'>" . $nov->Naslov() . "</h1>";
                echo "<div class='novost-div pdn'>";
                echo "<p class='novost-pa pdn'><b>Autor: </b>" . $nov->Autor() . "</p>";
                echo "<p class='novost-pd pdn'><b>Objavljeno: </b>" . $nov->DatumString() . "</p>";
                echo "</div><br>";
                echo "<div class='novost-data pdn'>" . $nov->DetaljnijiTekst() .  "</div>";
                $k = $baza->prepare("SELECT NOVOST, AUTOR, UNIX_TIMESTAMP(DATUM) AS 'DATUM', EMAIL, TEXT FROM KOMENTARI WHERE NOVOST = :n ORDER BY DATUM DESC");
                $k->bindValue(":n", $s, PDO::PARAM_INT);
                $k->execute();
                $coms = $k->fetchAll();
                if (!$k)
                    ProcessError($baza);
                $broj = intval(count($coms));
                echo "<div class='komentari'><h3>Komentari (" . $broj .")</h3>";
                echo "<form class='frm-cmnt' action='novosti.php' method='POST'>";
                echo "<table class='tbl-cmnt'><col style='width: 80px;'><col><tr class='prvi'><td colspan='2'>Ostavite komentar</td></tr>";
                echo "<tr><td>Ime</td><td><input class='inp' type='text' name='autor'></td></tr>";
                echo "<tr><td>email</td><td><input class='inp' type='text' name='email'></td></tr>";
                echo "<tr><td>Komentar</td><td><textarea rows='8' class='inp' cols='20' name='com'></textarea></td></tr>";
                echo "<tr><td colspan='2' class='zadnji'><input class='inp-btn' type='submit' value='Pošalji'></td></tr></table>";
                echo "<input type='hidden' name='vid' value='$s'>";
                echo "<input type='hidden' name='akcija' value='komentar'></form><hr>";
                foreach ($coms as $kom)
                {
                    echo "<div class='komentar'><div class='header'>";
                    $em = $kom['EMAIL'];
                    if (strlen($em) !== 0)
                        echo "Objavio: <a href='mailto:" . $kom['EMAIL'] . "'>" . $kom['AUTOR'] . "</a>";
                    else
                        echo "Objavio: " . $kom['AUTOR'];
                    echo ", <i>" . date("d.m.Y. (h:i)", $kom['DATUM']) . "</i></div><div class='data'>" . $kom['TEXT'] . "</div></div>";
                }
                echo "</div>";
            }
            function Nazad()
            {
                echo "<a href='#' onclick='window.history.back();'>Nazad</a>";
            }
            if (isset ($_REQUEST['vijest']))
                JednNovost($_REQUEST['vijest']);
            else if (isset ($_REQUEST['akcija']) && $_REQUEST['akcija'] === "komentar")
                {
                    $au = Skrati (htmlspecialchars($_REQUEST['autor']), 50);
                    $kom = Skrati (htmlspecialchars($_REQUEST['com']), 1024);
                    if (strlen($kom) < 10)
                    {
                        echo "<h1>Poslan tekst komentara sa manje od 10 znakova, komentar nije spašen!</h1>";
                        Nazad();
                        exit();
                    }
                    $email = Skrati (htmlspecialchars($_REQUEST['email']), 64);
                    $id = intval($_REQUEST['vid']);
                    $baza = InitBase();
                    $u = $baza->prepare("INSERT INTO KOMENTARI SET AUTOR = :au, TEXT = :kom, EMAIL = :em, NOVOST = :n");
                    $u->bindValue(":au", strlen($au) == 0 ? "Anonimni korisnik" : trim($au), PDO::PARAM_STR);
                    $u->bindValue(":kom", $kom, PDO::PARAM_STR);
                    $u->bindValue(":em", $email, PDO::PARAM_STR);
                    $u->bindValue(":n", $id, PDO::PARAM_INT);
                    $u->execute();
                    if (!$u)
                        ProcessError($baza);
                    #ne svidja mi se ovo, ali šta ću kada se koristi Ajax :D
                    echo "<h2>Komentar uspješno poslan!</h2>";
                    echo "<a href='#' onclick='document.location.href=\"index.html\";'>Naslovnica | WT-Learn</a>";

                }
            else
                SveNovosti();

            function Skrati ($sta, $vel)
            {
                $sta = trim($sta);
                if (strlen ($sta) > $vel)
                    return substr($sta, 0, $vel);
                return $sta;
            }
            ?>

        </div>
    </div>
</div>

</body>
</html>
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
        <div id="novosti" class="<?php if (isset ($_REQUEST['vijest'])) echo "no-scroll"; else echo ""; ?>">
            <?php
            require_once ("NovostPHP.php");
            require_once ("general.php");
            ini_set("display_errors", 1);
            header('Content-Type: text/html; charset=UTF-8');
            list ($user, $logovan, $nick, $sesija) = Sesija();
            function SveNovosti()
            {
                global $logovan;
                $u = DajNovosti(1); #sve novosti
                $novosti = array();
                $i = 0;
                #Fajl *MORA* biti UTF-8 bez BOM
                foreach ($u as $f)
                    $novosti[$i++] = new NovostSQL($f);
#                usort($novosti, array('NovostPHP', 'KriterijSortiranjaUNIX'));

                $html = <<<ETEXT
            <div class="novost">
                <h3 class="h">###NASLOV### ###DIV###<span class="autor">###AUTOR###</span><span class="datum">###DATUM###</span>
                </h3>
                <img class="slika-novost ###IMA_SLIKA###" src="###SLIKA###">
                <div class="data">###KRATKI###</div>
                <div class="clr"></div>
                <div class="lnk ###IMA_VISE### inline-div">
                    <a href="#" onclick="UcitajVijestSQL ('###FAJL###');">Više detalja</a>
                </div>
                <div class="lnk inline-div">
                    <a href="#" onclick="PrikaziKomentare ('###FAJL###');">###BR_K###</a>
                </div>
                <div id="komentar-###FAJL###">

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
                    $copy = str_replace("###BR_K###", ($c = $e->BrojKomentara()) > 0 ? "Komentara: $c" : "Nema komentara", $copy);
                    $copy = str_replace("###IMA_VISE###", $e->ImaDetaljnijegTeksta() ? "" : "invisible", $copy);
                    $hm = "<div class='admin-novost'><form class='admin-novost-frm' method='post' action='admin_site.php'> " .
                        "<button name='admin_akcija' value='brisi' type='submit' class='link-btn btn-green'>Briši</button>" .
                        "&nbsp;<button name='admin_akcija' value='izmijeni' type='submit' class='link-btn btn-green'>Izmijeni</button>" .
                        "<input type='hidden' name='novost' value='" . $e->ID() . "'>" .
                        " </form></div>";
                    if ($logovan)
                        $copy = str_replace("###DIV###", $hm, $copy);
                    else
                        $copy = str_replace("###DIV###", "", $copy);
                    echo $copy;
                }
            }
            function JednNovost($s, $preskoci = false)
            {
                global $logovan;

                    $a = DajNovost($s); #znam da nije bas najefikasnije, ali..
                    if (!$a) {
                        echo "<h2>Novost sa datim ID-om ne postoji!</h2>";
                        exit();
                    }
                    $nov = new NovostSQL($a, false);
                if (!$preskoci) {
                    echo "<h1 class='novost-h1'>" . $nov->Naslov() . "</h1>";
                    echo "<div class='novost-div pdn'>";
                    echo "<p class='novost-pa pdn'><b>Autor: </b>" . $nov->Autor() . "</p>";
                    echo "<p class='novost-pd pdn'><b>Objavljeno: </b>" . $nov->DatumString() . "</p>";
                    echo "</div><br>";
                    if ($logovan) {
                        $hm = "<div class='admin-novost odmakni'><form class='admin-novost-frm' method='post' action='admin_site.php'> " .
                            "<button name='admin_akcija' value='brisi' type='submit' class='link-btn btn-green'>Briši</button>&nbsp;" .
                            "<button name='admin_akcija' value='izmijeni' type='submit' class='link-btn btn-green'>Izmijeni</button>&nbsp;" .
                            (!$nov->DozvoljenoKomentarisanje() ?
                                "<button name='admin_akcija' value='dozvoli_komentare' type='submit' class='link-btn btn-green'>Dozvoli komentare</button>"
                                :
                                "<button name='admin_akcija' value='zabrani_komentare' type='submit' class='link-btn btn-green'>Zabrani komentare</button>") .
                            "<input type='hidden' name='novost' value='" . $nov->ID() . "'>" .
                            " </form></div>";
                        echo $hm . "<br>";
                    }
                    echo "<div class='novost-data pdn'>" . $nov->DetaljnijiTekst() . "</div>";
                } #end if - preskoci
                $baza = InitBase();
                $k = $baza->prepare("SELECT ID, NOVOST, AUTOR, UNIX_TIMESTAMP(DATUM) AS 'DATUM', EMAIL, TEXT FROM komentari WHERE NOVOST = :n ORDER BY DATUM DESC");
                $k->bindValue(":n", $s, PDO::PARAM_INT);
                $k->execute();
                $coms = $k->fetchAll();
                if (!$k)
                    ProcessError($baza);
                $broj = intval(count($coms));
                echo "<!-- %Start_Komentari% --><div class='komentari'><h3>Komentari (" . $broj .") " . ($nov->DozvoljenoKomentarisanje() ?
                    "" : " - Komentarisanje ove novosti nije dozvoljeno!") . "</h3>";
                if ($nov->DozvoljenoKomentarisanje())
                {
                    echo "<form class='frm-cmnt' action='novosti.php' method='POST'>";
                    echo "<table class='tbl-cmnt'><col style='width: 80px;'><col><tr class='prvi'><td colspan='2'>Ostavite komentar</td></tr>";
                    echo "<tr><td>Ime</td><td><input class='inp' type='text' name='autor' pattern='[\S* ]{3,50}' title='Minimalno tri a maksimalno 50 znakova! Dozvoljeni su alfanumerički znakovi kao i _, . i -'></td></tr>";
                    echo "<tr><td>email</td><td><input class='inp' type='email' name='email'></td></tr>";
                    echo "<tr><td>Komentar</td><td><textarea rows='8' class='inp' cols='20' name='com' title='Minimalno 10 znakova, maksimalno 1024'></textarea></td></tr>";
                    echo "<tr><td colspan='2' class='zadnji'><input class='inp-btn' type='submit' value='Pošalji'></td></tr></table>";
                    echo "<input type='hidden' name='vid' value='$s'>";
                    echo "<input type='hidden' name='akcija' value='komentar'></form><hr>";
                }

                foreach ($coms as $kom)
                {
                    echo "<div class='komentar'><div class='header'>";
                    $em = $kom['EMAIL'];
                    $hm = "<div class='admin-komentar'><form class='admin-komentar-frm' method='post' action='admin_site.php'> " .
                        "<button name='admin_akcija' value='brisi_komentar' type='submit' class='link-btn btn-green'  onclick=\"return confirm('Jeste li sigurni da želite izbrisati ovaj komentar?');\">Briši</button>" .
                        "<input type='hidden' name='komentar' value='" . $kom['ID'] . "'>" .
                        " </form></div>";
                    if (!$logovan)
                        $hm = "";
                    if (strlen($em) !== 0)
                        echo "Objavio: <a href='mailto:" . $kom['EMAIL'] . "'>" . $kom['AUTOR'] . "</a>";
                    else
                        echo "Objavio: " . $kom['AUTOR'];
                    echo ", <i>" . date("d.m.Y. (h:i)", $kom['DATUM']) . "</i>$hm</div><div class='data'>" . $kom['TEXT'] . "</div></div>";
                }
                echo "</div><!-- %End_Komentari% -->";
            }
            function Nazad()
            {
                echo "<a href='#' onclick='window.history.back();'>Nazad</a>";
            }
            if (isset ($_REQUEST['vijest']))
                JednNovost($_REQUEST['vijest'], isset ($_REQUEST['inline']) && $_REQUEST['inline'] === "true");
            else if (isset ($_REQUEST['akcija']) && $_REQUEST['akcija'] === "komentar")
                {
                    $au = Skrati (htmlspecialchars($_REQUEST['autor']), 50);
                    $kom = Skrati (htmlspecialchars($_REQUEST['com']), 1024);
                    if (strlen($kom) < 10)
                    {
                        Greska("Poslan tekst komentara sa manje od 10 znakova, komentar nije spašen!", false, 3);
                        Nazad();
                        exit();
                    }
                    if (strlen($au) < 3)
                    {
                        echo ("Polje autor mora imati barem 3 alfanumerička znaka, komentar nije spašen!");
                        Nazad();
                        exit();
                    }
                    $email = Skrati (htmlspecialchars($_REQUEST['email']), 64);
                    $id = intval($_REQUEST['vid']);
                    $baza = InitBase();
                    $u = $baza->prepare("INSERT INTO komentari SET AUTOR = :au, TEXT = :kom, EMAIL = :em, NOVOST = :n");
                    $u->bindValue(":au", strlen($au) == 0 ? "Anonimni korisnik" : trim($au), PDO::PARAM_STR);
                    $u->bindValue(":kom", $kom, PDO::PARAM_STR);
                    $u->bindValue(":em", $email, PDO::PARAM_STR);
                    $u->bindValue(":n", $id, PDO::PARAM_INT);
                    $u->execute();
                    if (!$u)
                        ProcessError($baza);
                    #ne svidja mi se ovo, ali šta ću kada se koristi Ajax :D
                    Success("Komentar uspješno poslan!", false, 2);
                    echo "<a href='#' onclick='document.location.href=\"index.html\";'>Naslovnica | WT-Learn</a>";

                }
            else
                SveNovosti();


            ?>

        </div>
    </div>
</div>

</body>
</html>
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
            header('Content-Type: text/html; charset=UTF-8');
            $dir = getcwd() . DIRECTORY_SEPARATOR . "novosti" . DIRECTORY_SEPARATOR;
            if (!isset ($_REQUEST['vijest'])) {

                #var_dump($aa);
                $fajlovi = array_filter(scandir($dir), function ($a) {
                    global $dir;
                    return is_file($dir . $a) && substr($a, strlen($a) - 4) === ".txt";
                });


                $novosti = array();
                $i = 0;
                #Fajl *MORA* biti UTF-8 bez BOM
                foreach ($fajlovi as $f)
                    $novosti[$i++] = new NovostPHP(file($dir . $f), $f);
                usort($novosti, array('NovostPHP', 'KriterijSortiranjaUNIX'));

                $html = <<<ETEXT
            <div class="novost">
                <h3 class="h">###NASLOV###<span class="autor">###AUTOR###</span><span class="datum">###DATUM###</span>
                </h3>
                <img class="slika-novost ###IMA_SLIKA###" src="###SLIKA###">
                <div class="data">###KRATKI###</div>
                <div class="clr"></div>
                <div class="lnk ###IMA_VISE###">
                    <a href="#" onclick="UcitajVijest ('###FAJL###');">Više detalja</a>
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
                    $copy = str_replace("###FAJL###", $e->ImeFajla(), $copy);
                    $copy = str_replace("###IMA_VISE###", $e->ImaDetaljnijegTeksta() ? "" : "invisible", $copy);
                    echo $copy;
                }
            }
            else
            {
                $s = $_REQUEST['vijest'];
                $nov = new NovostPHP(file($dir . $s));
                echo "<h1 class='novost-h1'>" . $nov->Naslov() . "</h1>";
                echo "<div class='novost-div pdn'>";
                echo "<p class='novost-pa pdn'><b>Autor: </b>" . $nov->Autor() . "</p>";
                echo "<p class='novost-pd pdn'><b>Objavljeno: </b>" . $nov->DatumString() . "</p>";
                echo "</div><br>";
                echo "<div class='novost-data pdn'>" . $nov->DetaljnijiTekst() .  "</div>";
            }
            ?>

        </div>
    </div>
</div>

</body>
</html>
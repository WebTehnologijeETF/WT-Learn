<?php
require_once ('config.php');
require_once ('NovostPHP.php');
require_once('Mail.php'); #Pear, php.ini include_path promijenjen
/*
 * Mislim da ovdje nema propustâ, nikakvih :D
 * Čak sam vodio računa i o naknadnoj promjeni POST parametara,
 * npr. ako neko sa nivoom 1 uđe da dodaje administratore
 * on neće imati opciju da doda admina sa manjim nivoom (veća priviegija)
 * ali će, ako je vješt, biti u mogućnosti naknadno da promijeni POST
 * parametre, pa npr. ako na formi nije mogao da odabere nivo polje
 * da bude 0 (najveći prioritet) on naknadno može promijeniti POST
 * parametar (kao i cijelo zaglavlje) akcija=dodaj_admina&nivo=0
 * i onda poslati takav zahtjev. Isto se dešava i ako izmijeni npr.
 * akcija=briši_admina&admin=enil da izbriše glavnog admina.
 * Zbog toga je urađena server validacija, a istestirano je mijenjanjem
 * POST parametara :)
 * */

ini_set("display_errors", 1);

if (!function_exists('boolval')) {
    function boolval($val) {
        return (bool) $val;
    }
}

$max_nivo = 10;
$check_nivo = true;
$mc_nivo = 1;
$sesija_time = 120; #2 minute, eksperimentalno
function InitBase()
{
    global $baza;
    global $hbaza;
    global $ubaza;
    global $pbaza;
    $b = $baza;
    $h = $hbaza;
    $veza = new PDO("mysql:dbname=$b;host=$h;charset=utf8", $ubaza, $pbaza);
    $veza->exec("set names utf8");
    unset ($b);
    unset ($h);
    return $veza;
}
function CheckNivo ()
{
    global $check_nivo;
    global $mc_nivo;
    if ($check_nivo)
        if ($_SESSION['nivo'] > $mc_nivo) #malo zezancije, admin koji može čitati vijesti i ostalo, ali ne može mijenjati/dodavati
        {
            Info("Dragi/a " . $_SESSION['nick'] . ", ti jesi administrator, ali imaš nivo pristupa > 1, a kao takav ne možeš mijenjati, brisati ili dodavati novosti/komentare/profile.<br> " .
                "Ovo je čisto radi zezancije, da mi neko kome sam dao admina ne spama ili uništi novosti :P", true, 3);
        }
}
function ProcessError (PDO $e)
{
    $greska = $e->errorInfo();
    print "<h1 style='color:red;'>SQL greška: <i>" . $greska[2] . "</i></h1>";
    exit();
}
function Greska ($s, $e = false, $i = 2)
{
    echo "<h$i class='greska'>$s</h$i>";
    if ($e)
        exit();
}
function Info ($s, $e = false, $i = 2)
{
    echo "<h$i class='info'>$s</h$i>";
    if ($e)
        exit();
}
function Success ($s, $e = false, $i = 2)
{
    echo "<h$i class='uspjeh'>$s</h$i>";
    if ($e)
        exit();
}
function Sesija ()
{
    global $sesija_time;
    $sesija = false;
    session_start();

    if (isset($_SESSION['sesija']) && (time() - $_SESSION['sesija'] > $sesija_time)) #15 min sesija
    {
        $sesija = isset ($_SESSION['user']) && $_SESSION['user'] !== "" ? true : false;
        session_unset();
        session_destroy();
        session_start();
    }
    $_SESSION['sesija'] = time();

    $user = "";
    $nick = "";
    $logovan = false;
    $nivo = 1000;
    if (isset ($_SESSION['user'])) {
        $user = $_SESSION['user'];
        $logovan = true;
        $nick = $_SESSION['nick'];
        $nivo = $_SESSION['nivo'];
    }
    return array($user, $logovan, $nick, $sesija, $nivo);
}
function ResetirajSifru ($u)
{
    Success("Ovo još ne radi :D", true, 1);
}
function PrikaziPanel()
{
    global $nick;
    $txt = <<<ETXT
    <form class="panel-forma" method="POST" action="admin_site.php"><div class='panel-div'>
    <table class="panel-tabela">
        <tr><td class="prvi" colspan="3"><b>$nick</b>, dobrodošli (<button class="link-btn fnt-mid" name="akcija" value="logout" type="submit">Odjavi se</button>)</td></tr>
        <tr class="drugi"><td>Uredi novosti</td><td>Uredi komentare</td><td>Profili</td></tr>
        <tr>
            <td><button class="link-btn" name="admin_akcija" value="dodaj" type="submit">Dodaj novost</button></td>
            <td><button class="link-btn" name="opcija" value="svi_komentari" type="submit">Svi komentari</button></td>
            <td><button class="link-btn" name="admin_akcija" value="izmijeni_admina" type="submit">Opcije profila</button></td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="sve_novosti" type="submit">Sve novosti</button></td>
            <td><button class="link-btn" name="opcija" value="komentari_danas" type="submit">Današnji</button></td>
            <td><button class="link-btn" name="admin_akcija" value="dodaj_admina" type="submit">Dodaj novog administratora</button></td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_bk" type="submit">Bez komentara</button></td>
            <td><button class="link-btn" name="opcija" value="komentari_7" type="submit">Zadnjih 7 dana</button></td>
            <td><button class="link-btn" name="admin_akcija" value="izbrisi_admina" type="submit">Ukloni administratora</button></td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_sk" type="submit">Sa komentarima</button></td>
            <td><button class="link-btn" name="opcija" value="komentari_15" type="submit">Zadnjih 15 dana</button></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_danas" type="submit">Današnje</button></td>
            <td><button class="link-btn" name="opcija" value="komentari_30" type="submit">Zadnjih 30 dana</button></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_3" type="submit">Zadnja 3 dana</button></td>
            <td><button class="link-btn" name="opcija" value="komentari_vijest" type="submit">Za novost sa ID-om:</button>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_7" type="submit">Zadnjih 7 dana</button></td>
            <td><input type="number" name="novost" min="1" value="1"></td></td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_30" type="submit">Zadnjih 30 dana</button></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_sd" type="submit">Sa 'detaljnije' tekstom</button></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><button class="link-btn" name="opcija" value="novosti_bd" type="submit">Bez 'detaljnije' teksta</button></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table></div>
</form>
ETXT;
    echo $txt;
}
function Sredi ($id, $def = "")
{
    return isset($_REQUEST[$id]) ? htmlspecialchars($_REQUEST[$id]) : $def;
}
function PrikaziReset()
{
    $u = Sredi("uname");
    $ftxt = <<< ETX
    <form class="login-forma" action="admin_site.php" method="POST">
        <table class="login-tabela">
            <tr><td class='prvi' colspan="2"><b>Resetiraj šifru</b></td></tr>
            <tr><td>Username</td><td><input autofocus required type="text" name="uname" class="inp" value="$u"></td></tr>
            <tr><td>e-mail</td>
            <td>
            <input type="email" name="email" required class="inp"></td></tr>
            <tr><td colspan="2" class="zadnji"><button type="submit" name="akcija" value="reset"
                                                      class="link-btn fnt-big">Resetiraj</button></td></tr>
        </table>
        <input type="hidden" name="admin_akcija" value="reset_pwd">
    </form>

ETX;
    echo $ftxt;
}
function PosaljiMail ($kome, $naslov, $data, array $cc = null, $from = "epajic1@etf.unsa.ba")
{
    global $mailp;
    $mail = Mail::factory("smtp",
        array(
            "host"     => "ssl://webmail.etf.unsa.ba",
            "username" => "epajic1@etf.unsa.ba",
            "password" => $mailp,
            "auth"     => true,
            "port"     => 465
        )
    );

    $headers = array("From" => $from, "Subject" => $naslov, "Content-Type"  => 'text/plain; charset=UTF-8');
    $body = $data;
    $mail->send($kome, $headers, $body);
    if (PEAR::isError($mail)) {
        Greska($mail->getMessage(), true, 1);
    }
    else return true;
}
function PrikaziLogin($fil)
{

    $f = basename($fil);
    $u = Sredi("uname");
    $ftxt = <<< ETX
    <form class="login-forma" action="$f" method="POST">
        <table class="login-tabela">
            <tr><td class='prvi' colspan="2"><b>Prijavite se</b></td></tr>
            <tr><td>Username</td><td><input autofocus type="text" name="uname" class="inp" value="$u"></td></tr>
            <tr><td>Šifra</td>
            <td>
            <input type="password" name="pname" class="inp"></td></tr>
            <tr><td colspan="2" class="zadnji"><input type="submit" name="prijava" value="Prijavi se"
                                                      class="link-btn fnt-big"></td></tr>
            <tr><td colspan="2"><hr></td><tr>
            <tr><td colspan="2">
            <input type="submit" class="link-btn"
                                                     name="reset_pwd" value="Zaboravljena šifra?">
            </td><tr>
        </table>
        <input type="hidden" name="akcija" value="login">
    </form>

ETX;
    echo $ftxt;
}
function DajNovosti ($i)
{
    $baza = InitBase();
    $upiti = array (1 => "WHERE 7 = 7",
        2 => "WHERE 0 = (SELECT COUNT(*) FROM komentari k WHERE n.ID = k.NOVOST)",
        3 => "WHERE 0 < (SELECT COUNT(*) FROM komentari k WHERE n.ID = k.NOVOST)",
        4 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()",
        5 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 3 DAY) AND NOW()",
        6 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()",
        7 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()",
        8 => "WHERE CHAR_LENGTH(TRIM(D_TEXT)) <> 0",
        9 => "WHERE CHAR_LENGTH(TRIM(D_TEXT)) = 0",
    );
    $str = "SELECT ID, AUTOR, NASLOV, UNIX_TIMESTAMP(DATUM) AS 'DATUM', K_TEXT, D_TEXT, SLIKA, KOMENTARISANJE FROM novosti n";

    $u = $baza->query($str . " " . $upiti[$i] . " ORDER BY DATUM DESC");
    if (!$u)
        ProcessError($baza);
    $niz = array();
    $br = 0;
    foreach ($u as $nov)
    {
        #nema SQL Injectiona...
        $z = $baza->query("SELECT COUNT(*) FROM komentari WHERE NOVOST = " . $nov['ID']);
        $nov['BROJ_K'] = intval($z->fetch()[0]);
        $niz[$br++] = $nov;
    }

    return $niz;
}
function DajKomentare ($i, $id)
{
    $baza = InitBase();
    $upiti = array (1 => "WHERE 7 = 7 ORDER BY k.NOVOST DESC",
        2 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ORDER BY DATUM DESC",
        3 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ORDER BY DATUM DESC",
        4 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW() ORDER BY DATUM DESC",
        5 => "WHERE DATUM BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ORDER BY DATUM DESC",
        6 => "WHERE k.NOVOST = :nov",
    );
    $str = "SELECT ID, AUTOR, TEXT, UNIX_TIMESTAMP(DATUM) AS 'DATUM', EMAIL, NOVOST FROM komentari k";

    $u = $baza->prepare($str . " " . $upiti[$i]);
    if ($i === 6)
        $u->bindValue(":nov", intval(XSS ($id)), PDO::PARAM_INT);
    $u->execute();
    if (!$u)
        ProcessError($baza);
    $niz = array();
    $f = $u->fetchAll();
    $br = 0;
    foreach ($f as $nov)
    {
        $niz[$br++] = $nov;
    }

    return $niz;
}

function PrikaziKomentare ($niz)
{
    if (count ($niz) === 0) {
        Success("Nema komentara!", false, 4);
        return;
    }
    $txt = <<<ETXT
            <div class='scroll-novosti'><table id="ajax-tabela" class='fix-table'>
            <tr class="novost-prvi"><td>ID</td><td class='novost-naslov'>Tekst</td><td>Autor</td>
            <td>Datum objave</td><td>ID Novosti</td><td>Akcija</td></tr>
ETXT;
    echo $txt;
    $stari = -1;
    foreach ($niz as $n)
    {
        $ID = $n['ID'];
        $NASLOV = $n['TEXT'];
        $AUTOR = $n['AUTOR'];
        $AUTOR = trim($n['EMAIL']) === "" ? $AUTOR : "<a class='aslink' href='mailto:" . $n['EMAIL'] . "'>$AUTOR</a>";
        $DATUM = date ("d.m.Y. (h:i)", $n['DATUM']);
        $IK = $n['NOVOST'];
        $AKCIJA = "<form class='admin-komentar' method='POST' action='admin_site.php'>";
        $AKCIJA .= "<button name='admin_akcija' value='brisi_komentar' type='submit'  onclick=\"return confirm('Jeste li sigurni da želite izbrisati ovaj komentar?');\" ";
        $AKCIJA .= "title='Odmah briše komentar, nema ponovnog upita o brisanju' class='link-btn btn-green'>Briši</button>";
        $AKCIJA .= "<input type='hidden' name='komentar' value='$ID'>";
        $AKCIJA .= "</form>";
        if ($stari !== $IK)
        {

            $no = DajNovost($IK);
            $a1 = Skrati($no['NASLOV'], 40);
            $a2 = Skrati ($no['AUTOR'], 20);
            $ttxt = <<< ABC
Novost (Naslov: '$a1', Autor: '$a2', ID: $IK)
ABC;
            $FRM = <<<ETX
            <form class="admin-komentar  not-float" method="POST" action="admin_site.php">
            <button name="admin_akcija" value="izmijeni" type="submit" class="link-btn btn-green">$ttxt</button>
            <input type="hidden" name="novost" value="$IK"></form>
ETX;
            echo "<tr class='novost-ostalo'><td title=\"" . $ttxt . "\" class='lijevo-atd' colspan='6'>$FRM</td></tr>";
            $stari = $IK;
        }
        echo "<tr class='novost-ostalo'><td>$ID</td><td class='novost-naslov' title=\"" . $NASLOV . "\">$NASLOV</td><td>" .
            "$AUTOR</td>" .
            "<td class='novost-datum' title='$DATUM'>$DATUM</td><td>$IK</td><td>$AKCIJA</td></tr>";
    }
    echo "</table></div>";
}
function PrikaziVijesti ($niz)
{
    if (count ($niz) === 0) {
        Success("Nema novosti!", false, 4);
        return;
    }
    $txt = <<<ETXT
            <div class='scroll-novosti'><table id="ajax-tabela" class='fix-table'>
            <tr class="novost-prvi"><td>ID</td><td class='novost-naslov'>Naslov</td><td>Autor</td>
            <td>Datum objave</td><td>Dozvoljeni komentari?</td><td>Broj komentara</td><td>Akcija</td></tr>
ETXT;
    echo $txt;
    foreach ($niz as $n)
    {
        $ID = $n['ID'];
        $NASLOV = $n['NASLOV'];
        $AUTOR = $n['AUTOR'];
        $DATUM = date ("d.m.Y. (h:i)", $n['DATUM']);
        $IK = boolval($n['KOMENTARISANJE']) ? "DA" : "NE";
        $BK = intval($n['BROJ_K']);
        $AW = <<< ETXT
        <form class='admin-komentar not-float' method='POST' action='admin_site.php'>
        <button name='opcija' value='komentari_vijest' type='submit' title='Izlistaj komentare' class='link-btn btn-green'>$BK</button>
        <input type='hidden' name='novost' value='$ID'>
        </form>
ETXT;
        $ZZ = $BK > 0 ? $AW : $BK;
        $AKCIJA = "<form class='admin-komentar' method='POST' action='admin_site.php'>";
        $AKCIJA .= "<button name='admin_akcija' value='brisi' type='submit' class='link-btn btn-green'>Briši</button>";
        $AKCIJA .= "&nbsp;<button name='admin_akcija' value='izmijeni' type='submit' class='link-btn btn-green'>Izmijeni</button>";
        $AKCIJA .= "<input type='hidden' name='novost' value='$ID'>";
        $AKCIJA .= "</form>";
        echo "<tr class='novost-ostalo'><td>$ID</td><td class='novost-naslov' title=\"" . $NASLOV ."\">$NASLOV</td><td>$AUTOR</td>" .
            "<td class='novost-datum' title='$DATUM'>$DATUM</td><td>$IK</td><td>$ZZ</td><td>$AKCIJA</td></tr>";
    }
    echo "</table></div>";
}
function PrikaziUpozorenje ($txt, $id, $idid, $idbaza, $btxt, $bazatxt)
{
    $ftxt = <<< ETX
    <form class="login-forma frm-wider" action="admin_site.php" method="POST">
        <table class="login-tabela">
            <tr><td colspan="3">$txt</td></tr>
            <tr><td><a class="link-btn slink" href="index.html">Naslovnica</a></td>
            <td><a class="link-btn slink" href="admin_site.php">Admin panel</a></td>
            <td><button type="submit" name="$bazatxt" value="$idbaza"
                                                      class="link-btn fnt-big">$btxt</button></td></tr>
        </table>
        <input type="hidden" name="$idid" value="$id">
    </form>
ETX;
    echo $ftxt;
}
function ObradiOpciju($sta, $id = 0)
{
    switch ($sta) {
        case "sve_novosti":
            PrikaziVijesti(DajNovosti(1));
            break;
        case "novosti_bk":
            PrikaziVijesti(DajNovosti(2));
            break;
        case "novosti_sk":
            PrikaziVijesti(DajNovosti(3));
            break;
        case "novosti_danas":
            PrikaziVijesti(DajNovosti(4));
            break;
        case "novosti_3":
            PrikaziVijesti(DajNovosti(5));
            break;
        case "novosti_7":
            PrikaziVijesti(DajNovosti(6));
            break;
        case "novosti_30":
            PrikaziVijesti(DajNovosti(7));
            break;
        case "novosti_sd":
            PrikaziVijesti(DajNovosti(8));
            break;
        case "novosti_bd":
            PrikaziVijesti(DajNovosti(9));
            break;
        case "komentari_danas":
            PrikaziKomentare(DajKomentare(2, $id));
            break;
        case "svi_komentari":
            PrikaziKomentare(DajKomentare(1, $id));
            break;
        case "komentari_7":
            PrikaziKomentare(DajKomentare(3, $id));
            break;
        case "komentari_15":
            PrikaziKomentare(DajKomentare(4, $id));
            break;
        case "komentari_30":
            PrikaziKomentare(DajKomentare(5, $id));
            break;
        case "komentari_vijest":
            PrikaziKomentare(DajKomentare(6, $id));
            break;
    }
}
function DajNovost ($id)
{
    $s = intval($id);
    $baza = InitBase();
    $u = $baza->prepare("SELECT ID, AUTOR, UNIX_TIMESTAMP(DATUM) AS 'DATUM', K_TEXT, D_TEXT, NASLOV, SLIKA, KOMENTARISANJE FROM novosti WHERE ID = :id ORDER BY DATUM DESC");
    $u->bindValue(":id", $s, PDO::PARAM_INT);
    $u->execute();
    if (!$u)
        ProcessError($baza);
    $arr = $u->fetch();
    #nema SQL Injectiona...
    $z = $baza->query("SELECT COUNT(*) FROM komentari WHERE NOVOST = " . $arr['ID']);
    $arr['BROJ_K'] = intval($z->fetch()[0]);
    return $arr;
}
function DodajVijest ($dodavanje, $id)
{
    $a = $dodavanje ? "Dodaj novu novost" : "Izmijeni novost (ID: $id)";
    $autor = $naslov = $kt = $dt = $link = "";
    $kom = "da";
    $inv = "invisible";
    $broj = 0;
    if (!$dodavanje)
    {
        $b = DajNovost($id);
        if (!$b) #nikad se ne bi trebalo desiti jer se prenosi preko hidden polja, ali za svaki slucaj
            Greska("Novost sa datim ID-om ne postoji!", true);
        $nov = new NovostSQL($b);
        $autor = $nov->Autor();
        $naslov = $nov->Naslov();
        $kt = $nov->KratkiTekst();
        $dt = $nov->DetaljnijiTekst();
        $link = $nov->LinkSlike();
        $kom = $nov->DozvoljenoKomentarisanje() ? "da" : "ne";
        $broj = $nov->BrojKomentara();
        if ($broj > 0)
            $inv = "";
    }
    $z = !$dodavanje ? "izmijeni" : "dodaj";
    $kom = $kom == 'da' ? 'checked' : '';
    $txt = $dodavanje ? "Dodaj novost" : "Izmijeni novost";
    $s = intval (Sredi ('novost', 0));
    $hm = <<< ETXT
            <form method="post" action="admin_site.php" class="izmjena-frm">
                    <table class='tbl-novost'><tr class='prvi'><td colspan="2">$a</td></tr>
                        <tr><td class="prva">Naslov</td>
                            <td class="druga"><input class='inp' type='text' name='naslov' value='$naslov' pattern="[\S* ]{10,50}" title="Minimalno 10 a maksimalno 200 znakova!">&nbsp;<span class="sgrey">Max. 200 znakova, ostatak će biti uklonjen, min. 10 znakova</span></td></tr>
                        <tr><td class="prva">Autor</td>
                            <td class="druga"><input class='inp' type='text' name='autor' value='$autor' pattern="[a-zA-Z0-9_\.\- čČćĆžŽđĐšŠ]{3,50}" title="Minimalno tri a maksimalno 50 znakova!">&nbsp;<span class="sgrey">Max. 50 znakova, ostatak će biti uklonjen, min. 3 znaka</span></td></tr>
                        <tr><td colspan="2">Kratki tekst</td></tr>
                        <tr><td colspan="2"><textarea class='a inp' name='k_text'>$kt</textarea></td></tr>
                        <tr><td colspan="2">Dugi tekst<br><span class="sgrey">Ako ovo polje ostane prazno novosti neće imati link 'detaljnije' (<b>Note:</b> komentarisanje vijesti će biti onemogućeno!)</span></td></tr>
                        <tr><td colspan="2"><textarea  class='b inp' name='d_text'>$dt</textarea></td></tr>
                        <tr><td colspan="2">Link slike<br><span class="sgrey">Ostavite polje prazno ako ne želite prikazati sliku</span></td></tr>
                        <tr><td colspan="2"><input class='inp' type='text' name='link' value=$link></td></tr>
                        <tr><td colspan="2"><input type='checkbox' name='komentarisanje' value="da" $kom>&nbsp;Dozvoli komentarisanje</td></tr>
                        <tr><td colspan="2" class='zadnji'>
                                <button name='baza' value='$z' type='submit' class='link-btn btn-green fnt-bigger'>$txt</button>
                            </td></tr>
                        <tr class="$inv"><td colspan="2">
                            <button name='baza' value='pobrisi_komentare' type='submit' class='link-btn btn-green' onclick="return confirm('Jeste li sigurni da želite izbrisati sve komentare za ovu novost?');">
                            Pobriši sve komentare ($broj) za ovu novost</button></td></tr>
                    </table>
                    <input type='hidden' name='novost' value='$s'>
            </form>
ETXT;
    echo $hm;
}
function ObradiAdminAkciju($i)
{
    switch ($i) {
        case "izmijeni":
            DodajVijest(false, intval($_REQUEST['novost']));
            break;
        case "dodaj":
            DodajVijest(true, 0);
            break;
        case "brisi":
            $i = intval($_REQUEST['novost']);
            PrikaziUpozorenje("Jesite li sigurni da želite izbrisati novost sa ID-om $i?",
                $i, "novost", "brisi", "Briši", "baza");
            break;
        case "brisi_komentar":
            SQLObrisi("komentari", intval($_REQUEST['komentar']));
            $i = $_REQUEST['komentar'];
            Success("Komentar sa ID-om $i uspješno izbrisan!");
            break;
        case "dozvoli_komentare":
            ToggleComments($_REQUEST['novost'], true);
            $i = $_REQUEST['novost'];
            Success("Komentari za novost sa ID-om $i su uspješno omogućeni!");
            break;
        case "zabrani_komentare":
            ToggleComments($_REQUEST['novost'], false);
            $i = $_REQUEST['novost'];
            Success("Komentari za novost sa ID-om $i su uspješno zabranjeni!");
            break;
        case "dodaj_admina":
            UrediAdmin(true, "");
            break;
        case "izmijeni_admina":
            UrediAdmin(false, $_SESSION['user']);
            break;
        case "izbrisi_admina":
            IzlistajAdmine ();
            break;
        case "potvrda_brisanja_admin":
            $i = XSS($_REQUEST['admin']);
            PrikaziUpozorenje("Jesite li sigurni da želite izbrisati administratora '$i'?",
                $i, "admin", "brisi_admina", "Briši", "baza");
            break;
    }
}
function DajRandomPass ($i = 16)
{
    return substr(str_shuffle(md5(microtime())), 0, $i);
}
function IzlistajAdmine ()
{
    $b = InitBase();
    $k = $b->query("SELECT * FROM korisnici");
    if (!$k)
        ProcessError($b);
    $niz = $k->fetchAll();
    if (count ($niz) === 1) {
        Success("Vi ste jedini administrator :)", false, 4);
        return;
    }
    if (count ($niz) === 0) {
        Success("Nema administratora? (nonsense, ali eto xD)", false, 4);
        return;
    }
    $txt = <<<ETXT
            <div class='scroll-novosti'><table id="ajax-tabela" class='fix-table admin-tbl'>
            <tr class="novost-prvi"><td>ID</td><td class='novost-naslov'>Username</td><td>Nick</td>
            <td>e-mail</td><td class="npr">Nivo pristupa</td><td>Akcija</td></tr>
ETXT;
    echo $txt;
    foreach ($niz as $n)
    {
        $ID = $n['ID'];
        $UNAME = $n['IME'];
        $NICK = $n['NICK'];
        $mn = $_SESSION['nivo'];
        $NIVO = $n['NIVO'];
        $EMAIL = "<a class='aslink' href='mailto:" . $n['EMAIL'] . "'>" .$n['EMAIL'] . "</a>";
        $AKCIJA = "<form class='admin-komentar not-float' method='POST' action='admin_site.php'>";
        $AKCIJA .= "<button name='admin_akcija' value='potvrda_brisanja_admin' type='submit' ";
        $AKCIJA .= "title='Briše administratora' class='link-btn btn-green'>Briši</button>";
        $AKCIJA .= "<input type='hidden' name='admin' value='$UNAME'>";
        $AKCIJA .= "</form>";
        if ($mn >= $NIVO && intval($mn) !== 0)
            $AKCIJA = "Nemate privilegija!";
        if ($UNAME === $_SESSION['user'])
            $AKCIJA = "To ste vi";
        echo "<tr class='novost-ostalo'><td class='td1'>$ID</td><td class='td2'>$UNAME</td><td class='td3'>" .
            "$NICK</td>" .
            "<td class='td4'>$EMAIL</td><td class='td5'>$NIVO</td><td class='td6'>$AKCIJA</td></tr>";
    }
    echo "</table></div>";
}
function SQLObrisi ($tabela, $id)
{
    CheckNivo();
    $b = InitBase();
    #ne moze SQL injetion na $tabela, al nikako
    $u = $b->prepare("DELETE FROM $tabela WHERE ID = :id");
    $u->bindValue(":id", $id, PDO::PARAM_INT);
    $u->execute();
    if (!$u)
        ProcessError($b);
}
function SQLObrisiKomentare ($novost)
{
    CheckNivo();
    $b = InitBase();
    #ne moze SQL injetion na $tabela, al nikako
    $u = $b->prepare("DELETE FROM komentari WHERE NOVOST = :id");
    $u->bindValue(":id", $novost, PDO::PARAM_INT);
    $u->execute();
    if (!$u)
        ProcessError($b);
}
function SQLObrisiAdmin ($uname)
{
    CheckNivo();
    $b = InitBase();
    $u = $b->prepare("DELETE FROM korisnici WHERE IME = :id");
    $u->bindValue(":id", $uname, PDO::PARAM_STR);
    $u->execute();
    if (!$u)
        ProcessError($b);
}
function Skrati ($sta, $vel)
{
    $sta = trim($sta);
    if (strlen ($sta) > $vel)
        return substr($sta, 0, $vel);
    return $sta;
}
function XSS ($s)
{
    return htmlspecialchars($s);
}
function SkratiXSS ($s, $len = 0)
{
    if ($len !== 0)
        return Skrati(XSS($s), $len);
    else
        return XSS($s);
}
function ToggleComments ($id, $enable)
{
    CheckNivo();
    $bb = InitBase();
    $u = $bb->prepare("UPDATE novosti SET KOMENTARISANJE = :da WHERE ID = :id");
    $u->bindValue(":id", intval ($id), PDO::PARAM_INT);
    $u->bindValue(":da", boolval($enable), PDO::PARAM_BOOL);
    $u->execute();
    if (!$u)
        ProcessError($bb);
}
function UrediNovost($str)
{
    $b1 = InitBase();
    $u = $b1->prepare($str);
    $aut = SkratiXSS($_REQUEST['autor'], 50);
    $nas = SkratiXSS($_REQUEST['naslov'], 200);
    if (strlen($aut) < 3)
        Greska("Polje 'Autor' mora imati barem 3 znaka!", true, 1);
    if (strlen($nas) < 10)
        Greska("Polje 'naslov' mora imati barem 10 znakova!", true, 1);
    if (strlen(trim(XSS($_REQUEST['k_text']))) < 15)
        Greska("Polje 'kratki tekst' mora imati barem 15 znakova!", true, 1);
    $u->bindValue(":u", $aut, PDO::PARAM_STR);
    $u->bindValue(":n", $nas, PDO::PARAM_STR);
    $u->bindValue(":kt", XSS($_REQUEST['k_text']), PDO::PARAM_STR);
    $u->bindValue(":dt", XSS($_REQUEST['d_text']), PDO::PARAM_STR);
    $u->bindValue(":s", XSS($_REQUEST['link']), PDO::PARAM_STR);
    $u->bindValue(":k", boolval(XSS($_REQUEST['komentarisanje']) == "da" ? true : false), PDO::PARAM_BOOL);
    return array($b1, $u, $aut, $nas);
}
function PripremiAdmina ($str, $dodavanje)
{
    $uname = XSS($_REQUEST['uname']);
    $nick = XSS($_REQUEST['nick']);
    $pass1 = XSS($_REQUEST['pass1']);
    $pass2 = XSS($_REQUEST['pass2']);
    $email = XSS ($_REQUEST['email']);
    if ($pass1 !== $pass2)
        Greska("Passwordi nisu isti!", true, 1);
    if (($aa = preg_match("/.{5,32}/", $pass1)) === false || $aa === 0)
        Greska("Polje 'password' mora imati barem 5 a najviše 32 znaka!", true, 1);
    $nivo = intval(XSS($_REQUEST['nivo']));
    global $max_nivo;
    if ($nivo < 0 || $nivo > $max_nivo)
        Greska("Polje 'nivo' može imati vrijednosti iz opsega [0, $max_nivo]!", true, 1);
    if (intval($_SESSION['nivo']) >= $nivo && $dodavanje && intval($_SESSION['nivo']) !== 0)
        Greska("Ne možete dodati admina sa nivoom pristupa koji je jednak ili manji od vašeg!", true, 1);
    if (($aa = preg_match("/[a-zA-Z0-9_\.\-~]{3,32}/", $uname)) === false || $aa === 0)
        Greska("Polje 'Username' mora imati barem 3, a najviše 32 alfanumerička znaka! Dozvoljeni su i znakovi '_', '.' i '-'", true, 1);
    if (($aa = preg_match("/[a-zA-Z0-9_\.\- čČćĆžŽđĐšŠ]{3,32}/", $nick)) === false || $aa === 0)
        Greska("Polje 'nick' mora imati barem 3, a najviše 32 alfanumerička znaka! Dozvoljeni su i znakovi '_', '.' i '-'", true, 1);
    if (!$dodavanje && $_SESSION['user'] !== $uname) #možda je nekako zaobiđena procesudra 'readonly' polja, što nije teško
        Greska("Ne možete mijenjati username!", true, 2);
    if (!$dodavanje && intval($_SESSION['nivo']) !== intval($nivo)) #možda je nekako zaobiđena procesudra 'readonly' polja, što nije teško
        Greska("Ne možete mijenjati vlastiti nivo pristupa!", true, 2);
    $b1 = InitBase();
    $u = $b1->prepare($str);
    $pass =  md5($pass1 . "Moj.H1sh-or-S7l1"); #malo salta :D
    $u->bindValue(":ime", $uname, PDO::PARAM_STR);
    $u->bindValue(":nick", $nick, PDO::PARAM_STR);
    $u->bindValue(":pass", $pass, PDO::PARAM_STR);
    $u->bindValue(":email", $email, PDO::PARAM_STR);
    $u->bindValue(":nivo", $nivo, PDO::PARAM_INT);
    return array($b1, $u, $uname, $nick);
}
function UrediAdmin ($dodavanje, $id)
{
    global $max_nivo;
    $z = $dodavanje ? "dodaj_admina" : "izmijeni_admina";
    $ro = $dodavanje ? "" : "readonly";
    $uname = $nick = $pass1 = $email = "";
    $min =  1;
    $text = "Dodaj admina";
    $i = intval($_SESSION['nivo']);
    $ko = $_SESSION['user'];
    $min = $i === intval($max_nivo) ? $max_nivo : ($i === 0 ? 0 : ($dodavanje ? $i + 1 : $i));
    $id = XSS($id);
    global $max_nivo;
    $af1 = "autofocus";
    $af2 = "";
    if (!$dodavanje)
    {
        $af2 = "autofocus";
        $af1 = "";
        $text = "Izmijeni profil";
        list($ok, $uname, $nick, $nivo, $email, $pass) = DajAdmina($id);
    }
    $dodaj = $dodavanje ? "Dodaj novog admina" : "Izmijeni admina '$uname'";
    $txt = <<< ETXT
    <form class="izmijeni-admin-frm" action="admin_site.php" method="post">
        <table class="tbl-afrm">
            <tr>
                <td colspan="2" class="prvi">$dodaj</td>
            </tr>
            <tr>
                <td class="prva">Username</td>
                <td><input class="inp" type="text" name="uname" value="$uname" $ro $af1 required="required" pattern="[a-zA-Z0-9_\.\-~]{3,32}" title="Username mora imati minimalno 3 a maksimalno 32 znaka,\nmora se sastojati od alfanumeričkih znakova i sljedećih znakova: '_', '-' i '.'"></td>
            </tr>
            <tr>
                <td class="prva">Nick</td>
                <td><input class="inp" type="text" name="nick" value="$nick" $af2 required="required" pattern="[a-zA-Z0-9_\.\- čČćĆžŽđĐšŠ]{3,32}" title="Nick mora imati minimalno 3 a maksimalno 32 znaka,\nmora se sastojati od alfanumeričkih znakova i sljedećih znakova: '_', '-' i '.'"></td>
            </tr>
            <tr>
                <td class="prva">Email</td>
                <td>
                    <input class="inp" type="email" name="email" value="$email" required="required">
                    <br><span class="sgrey">Ako zaboravite šifru, možete dobit novu isključivo na ovaj mail</span>
                </td>

            </tr>
            <tr>
                <td class="prva">Password</td>
                <td><input class="inp" type="password" id="pass1" name="pass1" value="$pass1" required="required" pattern=".{5,32}" title="Password mora imati minimalno 5 znakova, a maksimalno 32!"></td>
            </tr>
            <tr>
                <td class="prva">Ponovo, password</td>
                <td><input class="inp" type="password" name="pass2" oninput="provjeri(this);" value="$pass1" required="required" pattern=".{5,32}" title="Password mora imati minimalno 5 znakova, a maksimalno 32!">
                <script language='javascript' type='text/javascript'>
                    function provjeri(i)
                    {
                        if (i.value != document.getElementById('pass1').value)
                            i.setCustomValidity('Passwordi nisu isti!');
                        else i.setCustomValidity('');
                    }

                </script>
                </td>
            </tr>
            <tr>
                <td class="prva">Nivo pristupa</td>
                <td>
                    <span class="sgrey">Samo glavni admini imaju nivo pristupa 0 i samo oni mogu dodijeliti novog admina sa istim nivoom pristupa. Ostali mogu dodijeliti samo veći nivo pristupa</span>
                    <br><br><input class="inp" type="number" name="nivo" value="$min" min="$min" max="$max_nivo" required="required" $ro>
                    <span class="sgrey">&nbsp;Manji broj - veće privilegije</span>
                </td>
            </tr>
            <tr>
                <td class="zadnji" colspan="2"><button name='baza' value='$z' type='submit' class='link-btn btn-green fnt-bigger'>$text</button></td>
            </tr>
        </table>
        <input type='hidden' name='admin' value='$id'>
    </form>
ETXT;
    echo $txt;

}

function DajAdmina($user)
{
    $bz = InitBase();
    $u = $bz->prepare("SELECT * FROM korisnici WHERE IME = :id");
    $u->bindValue(":id", XSS($user), PDO::PARAM_STR);
    $u->execute();
    $rr = $u->fetch();
    $ok = false;
    if (!$u)
        ProcessError($bz);
    $ok = !$rr ? false : count($rr) > 0;
    $uname = $rr['IME'];
    $nick = $rr['NICK'];
    $nivo = $rr['NIVO'];
    $pass = $rr['PASS'];
    $email = $rr['EMAIL'];
    return array($ok, $uname, $nick, $nivo, $email, $pass);
}

function ObradiBazaAkciju($sta)
{
    switch ($sta) {
        case "dodaj":
            $str = "INSERT INTO novosti SET AUTOR = :u, NASLOV = :n, K_TEXT = :kt, D_TEXT = :dt, SLIKA = :s, KOMENTARISANJE = :k";
            list($b1, $u, $aut, $nas) = UrediNovost($str);
            $u->execute();
            if (!$u)
                ProcessError($b1);
            Success("Dodana novost od autora '$aut' sa naslovom '$nas'!");
            break;
        case "izmijeni":
            $str = "UPDATE novosti SET AUTOR = :u, NASLOV = :n, K_TEXT = :kt, D_TEXT = :dt, SLIKA = :s, KOMENTARISANJE = :k WHERE ID = :id";
            list($b1, $u, $aut, $nas) = UrediNovost($str);
            $iid = intval(XSS($_REQUEST['novost']));
            $u->bindValue(":id", $iid, PDO::PARAM_INT);
            $u->execute();
            if (!$u)
                ProcessError($b1);
            Success("Izmijenjena novost sa ID-om '$iid'!");
            break;
        case "brisi":
            SQLObrisi("novosti", intval(XSS($_REQUEST['novost'])));
            Success("Uspješno izbrisana novost!");
            break;
        case "dodaj_admina":
            list($ok, $uname, $nick, $nivo, $email, $pass) = DajAdmina(XSS($_REQUEST['uname']));
            if ($ok)
                Greska("Adminisrator sa username-om '$uname' već postoji!", true, 1);

            $str = "INSERT INTO korisnici SET IME = :ime, NICK = :nick, PASS = :pass, EMAIL = :email, NIVO = :nivo";
            list ($bz, $u, $uname, $nick) = PripremiAdmina($str, true);
            $u->execute();
            if (!$u)
                ProcessError($bz);
            Success("Dodan admin '$uname'!");;
            break;
        case "izmijeni_admina":
            $str = "UPDATE korisnici SET IME = :ime, NICK = :nick, PASS = :pass, EMAIL = :email, NIVO = :nivo WHERE IME = :id";
            list ($bz, $u, $uname, $nick) = PripremiAdmina($str, false);
            $u->bindValue(":id", XSS($_REQUEST['admin']), PDO::PARAM_STR);
            $u->execute();
            if (!$u)
                ProcessError($bz);
            Success("Izmijenjen profil ($uname)!");
            $_SESSION['nick'] = $nick;
            break;
        case "brisi_admina":
            #moramo se zastititi od mijenjanja POST parametara...
            list($ok, $uname, $nick, $nivo, $email, $pass) = DajAdmina(XSS($_REQUEST['admin']));
            if (!$ok)
                Greska("Admin ne postoji!", true, 2);
            if ($uname === $_SESSION['user'])
                Greska("Okay, dosta stvari može, ali ne možeš brisati sebe :D", true, 2);
            $i = intval($_SESSION['nivo']);
            $nivo = intval($nivo);
            if ($i >= $nivo && $i != 0)
                Greska("Admin kojega pokušavate brisati ima veći ili jednak nivo pristupa! Brisanje nije moguće!", true, 1);
            $uname = XSS($_REQUEST['admin']);
            SQLObrisiAdmin($uname);
            Success("Uspješno izbrisan admin '$uname''!");
            break;
        case "pobrisi_komentare":
            $nov = intval(XSS($_REQUEST['novost']));
            SQLObrisiKomentare($nov);
            Success("Uspješno izbrisani svi komentari za novost sa ID-om $nov!", false, 2);
            break;

    }
}
?>
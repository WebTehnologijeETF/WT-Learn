<?php
    require_once ("general.php");
    list ($user, $logovan, $nick, $sesija, $nivo) = Sesija();
//$pass =  md5("admin" . "Moj.H1sh-or-S7l1");
//$baza = InitBase();
//$a = $baza->exec("INSERT INTO korisnici SET IME='admin', PASS='$pass'");
//var_dump($a);
//if (!$a)
//    ProcessError($baza);
//exit();
?>
<!DOCTYPE html>
<html class="admin-html">
<head lang="en">
    <link rel="stylesheet" href="main.css">
    <meta charset="UTF-8">
    <title>WT Learn | Admin panel</title>
</head>
<body class="l-admin admin-body">
<div class="admin-frame">
    <div class="admin-head">
        <a href="admin_site.php">Admin panel</a> | <a href="index.html">WT Learn</a>
    </div>
    <div id="main-cont" class="ewhite">
        <?php
            if (isset($_REQUEST['baza_reset']))
            {
                $uname = XSS($_REQUEST['admin']);
                $pwd = DajRandomPass();
                $base = InitBase();
                $uu = $base->prepare("UPDATE korisnici SET PASS = :pass WHERE IME = :ime");
                $uu->bindValue(":pass", md5 ($pwd . "Moj.H1sh-or-S7l1"), PDO::PARAM_STR);
                $uu->bindValue(":ime", $uname, PDO::PARAM_STR);
                $uu->execute();
                if (!$uu)
                    ProcessError($base);
                $data = <<< MAIL
Poštovani $uname,

zatražili ste resetovanje vašeg passworda, pa je naš sistem isti i resetovao.
Mi znamo da je ovdje trebao biti neki link sa kojim ćete vi potvrditi resetovanje passworda, ali to još nije implementirano :(

Vaš novi password je: $pwd

Molimo da ga što prije promijenite!

Lijep pozdrav,
WT-Learn tim.
MAIL;
                list($ok1, $uname1, $nick1, $nivo1, $mail, $p1) = DajAdmina($uname);
                PosaljiMail($mail, "WT-Learn::WebMaster - reset passworda", $data);
                Success("Uspješno promijenjena šifra za admina '$uname!'<br>Poslan vam je email na '$mail'.<br>" .
                    "Provjerite <i>Junk</i> folder. Ako ne dobijete mail za 5 minuta, pokušajte ponovo!", false, 4);
                GOTO KRAJ;
            }
            if ($sesija && count($_REQUEST) > 0)
            {
                Info ("Vaša sesija je istekla, prijavite se ponovo", false, 3);
                PrikaziLogin(__FILE__);
                GOTO KRAJ;
            }
            if (isset($_REQUEST['akcija']) && $_REQUEST['akcija'] == "login")
            {
                $nick = "";
                if (!isset($_REQUEST['uname']))
                    goto NASTAVAK;
                $us = htmlspecialchars($_REQUEST['uname']);
                if (isset($_REQUEST['reset_pwd']))
                {
                    PrikaziReset();
                    goto KRAJ;
                }
                else
                {
                    list($r, $user, $nick, $nivo, $email, $pass) = DajAdmina($us);
                    if (!$r)
                    {
                        Greska("Korisnik sa nickom '$us' ne postoji!");
                        $logovan = false;
                        goto NASTAVAK;
                    }
                    $p = htmlspecialchars($_REQUEST['pname']);
                    if (md5($p . "Moj.H1sh-or-S7l1") === $pass)
                    {
                        $logovan = true;
                        $_SESSION['user'] = $user;
                        $_SESSION['nick'] = $nick;
                        $_SESSION['nivo'] = $nivo;
                    }
                    else {
                        Greska("Netačni login podaci!");
                        $logovan = false;
                        goto NASTAVAK;
                    }
                }
            }
        else if (isset($_REQUEST['akcija']) && $_REQUEST['akcija'] == "logout")
        {
            session_destroy();
            session_unset();
            $logovan = false;
            $user = "";
        }
        else if (isset($_REQUEST['akcija']) && $_REQUEST['akcija'] == "reset")
        {
            $un = XSS($_REQUEST['uname']);
            $em = XSS($_REQUEST['email']);
            list($ok, $uname, $nick, $nivo, $email, $pass) = DajAdmina($un);
            if (!$ok || count($ok) == 0)
            {
                Greska ("Ne postoji administrator sa username-om '$un'!");
                PrikaziReset();
                goto KRAJ;
            }
            if ($email !== $em)
            {
                Greska ("Pogrešan email '$em'!");
                PrikaziReset();
                goto KRAJ;
            }
            PrikaziUpozorenje("Jesite li sigurni da želite resetirati šifru administratora '$uname'<br>Dobiti ćete novu šifru na vaš mail?",
                $uname, "admin", "resetuj_sifru", "Resetiraj", "baza_reset");
            goto KRAJ;
        }
        if (!$logovan) #mora, ako je istekla sesija a neko uradio refresh, a bili neki parametri u POST req-u
            goto NASTAVAK;
        if (isset($_REQUEST['opcija']))
        {
            ObradiOpciju($_REQUEST['opcija'], isset($_REQUEST['novost']) ? intval(XSS($_REQUEST['novost'])) : 0);
            goto KRAJ;
        }
        else if (isset($_REQUEST['admin_akcija']))
        {
            ObradiAdminAkciju($_REQUEST['admin_akcija']);
            goto KRAJ;
        }
        else if (isset($_REQUEST['baza']))
        {
            $sta = $_REQUEST['baza'];
            if ($sta !== "dodaj_admina" && $sta !== "izmijeni_admina")
                CheckNivo();
            ObradiBazaAkciju($sta);
            goto KRAJ;
        }
        NASTAVAK:
            if (!$logovan)
                PrikaziLogin(__FILE__);
            else {
                PrikaziPanel();
            }
        goto KRAJ;
        KRAJ:

        ?>

    </div>
    <div class='footer'>Copyright &copy; 2014 -
        <script>
            document.write(new Date().getFullYear());
        </script>
        &nbsp;by Enil Pajić
    </div>
</div>

</body>
</html>
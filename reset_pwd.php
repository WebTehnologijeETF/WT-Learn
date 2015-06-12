<!doctype html>
<html>
<head>
    <title>Resetiranje passworda | WT-Learn</title>
    <link rel="stylesheet" href="main.css">
    <meta charset="UTF-8">
</head>
<body>
<?php
    require_once ('general.php');
    if (isset($_GET['k']) && isset($_GET['u']))
    {
        $vr = 86400; #24h
        $k = XSS($_GET['k']);
        $u = XSS($_GET['u']);
        $baza = InitBase();
        $s = (time() - $vr);
        $uu = $baza->prepare("SELECT USER, PASS, UNIX_TIMESTAMP(VRIJEME), STATUS FROM reset_pwd WHERE USER = :u AND PASS = :p AND STATUS = 'valid' AND UNIX_TIMESTAMP(VRIJEME) > :v");
        $uu->bindValue(":u", $u, PDO::PARAM_STR);
        $uu->bindValue(":p", $k, PDO::PARAM_STR);
        $uu->bindValue(":v", $s, PDO::PARAM_INT); # AND VR > :v
        $uu->execute();
        if (!$uu)
            ProcessError($baza);
        $r = $uu->fetch();
        if (!$r)
            Greska("Niste zatražili resetovanje šifre, vaš link je istekao ili ste već jednom resetirali šifru sa ovim linkom!<br><a href = \"index.html\">WT - Learn | Naslovnica</a>", true, 1);
        #mrsko mi praviti formu za ponovni unos passworda (što je najbolje), to ćemo kasnije dodati, sad sam umoran :D
        #pogotovo umoran od HTML-a
        $p = DajRandomPass();
        $pass = md5($p . "Moj.H1sh-or-S7l1");
        $rt = $baza->prepare("UPDATE reset_pwd SET STATUS = 'used' WHERE USER = :u AND PASS = :p");
        $rt->bindValue(":u", $u, PDO::PARAM_STR);
        $rt->bindValue(":p", $k, PDO::PARAM_STR);
        $rt->execute();
        if (!$rt)
            ProcessError($baza);
        $t = <<<MAIL
Poštovani, $u

uspješno ste resetirali password. Vaš novi password je: $p

Molimo da ga što prije promijenite!



WT-Learn tim!
MAIL;
        $zz = $baza->prepare("SELECT EMAIL, IME FROM korisnici WHERE IME = :u");
        $zz->bindValue(":u", $u, PDO::PARAM_STR);
        $zz->execute();
        $mail = $zz->fetch()['EMAIL'];
        $rt = $baza->prepare("UPDATE korisnici SET PASS = :p WHERE IME = :u");
        $rt->bindValue(":u", $u, PDO::PARAM_STR);
        $rt->bindValue(":p", $pass, PDO::PARAM_STR);
        $rt->execute();
        if (!$rt)
            ProcessError($baza);
        PosaljiMail($mail,"WT-Learn | Password je resetiran", $t);
        Success("Password je uspješno promijenjen i poslan na mail '$mail'.<br>Molimo provjerite mail i što prije promijeniti password kroz Admin panel!<br><br><a href = \"index.html\">WT - Learn | Naslovnica</a>");
    }

?>

</body>
</html>

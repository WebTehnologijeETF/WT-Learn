<?php
require_once ('../general.php');
setlocale(LC_CTYPE, "en_US.UTF-8");
if (isset ($_REQUEST['atid']) && isset ($_REQUEST['code']))
{
    echo ATiraj(intval($_REQUEST['atid']), InitBase());
    exit();
}
if (isset ($_REQUEST['zadid']) && isset ($_REQUEST['code']))
{

}
function ATiraj ($id, PDO $b) #da ne otvaram konekciju milion puta
{
    $u = $b->prepare("SELECT ID, CODE FROM at_autotestovi WHERE ID = :id");
    $u->bindValue(":id", intval ($id), PDO::PARAM_INT);
    $u->execute();
    $r = $u->fetch();
    if (!$u)
        ProcessError($b);
    if (!$r)
        return -1; #AT sa datim ID-om ne postoji!
    $co = $r['CODE'];
    $code = $_REQUEST['code'];
    if (strpos ($code, "</script>" === false))
        Greska("HTML kôd nije validan! (nema dodanog JS kôda)", true);
    $i = strpos ($code, "</script>");
    $code = substr_replace($code,  PHP_EOL . $co . PHP_EOL, $i, 0);
    try
    {
        #TODO razmisliti koji je bolji pristup, ako je asinhron onda
        #nemamo info kada je završeno izvršavanje...
        #ExecAsync("phantomjs.exe at.js " . escapeshellarg($code));
        $a = array();
        $b = array();
        echo exec("phantomjs.exe at.js " . escapeshellarg($code) . " > test.txt", $b, $a);
        print_r ($a);
        print_r ($b);
    }
    catch (Exception $e)
    {
        Greska("GREŠKA: " . PHP_EOL . $e->getMessage(), true, 3);
    }
    return 1;
}
#pokreće u pozadini, dozvoljavajući skripti da nastavi
function ExecAsync ($cmd) #by  Arno van den Brink (http://php.net/manual/en/function.exec.php#86329)
{
    if (substr(php_uname(), 0, 7) == "Windows")
        pclose(popen("start /B ". $cmd, "r"));

    else
        exec($cmd . " > /dev/null &");
}
?>
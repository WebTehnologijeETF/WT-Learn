<?php
/**
 * Created by PhpStorm.
 * User: Enil
 * Date: 18.5.2015
 * Time: 16:09
 */
function InitBase()
{
    $veza = new PDO("mysql:dbname=wtlearn;host=localhost;charset=utf8", "local", "password");
    $veza->exec("set names utf8");
    return $veza;
}
function ProcessError (PDO $e)
{
    $greska = $e->errorInfo();
    print "<h1 style='color:red;'>SQL greška: <i>" .$greska[2] . "</i></h1>";
    exit();
}
?>
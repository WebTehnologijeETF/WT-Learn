<?php
    /*
     * Ovaj config fajl je namijenjen da se koristi vidi
     * čak i na Github-u, čuva samo podatke za pristup bazi
     * koja je lokalna.
     *
     * */
namespace Config;
    #baza na koju ćemo se 'kačiti'
    $baza = "wtlearn";
    #korisničko ime
    $ubaza = "local";
    #password za mail, ovo ćemo ostaviti prazno prilikom pushanja
    #na GitHub, inače ga treba popuniti pa da mail ne dolazi u Junk
    $mailp = "";
    #njegov password
    $pbaza = "password";
    $hbaza = "localhost";
?>
<?php
class NovostPHP {
    private $datum, $naslov, $autor, $kratki = "", $dugi = "", $slika, $ime;
    function __construct ($s, $fajl = "")
    {
        #Fajl MORA biti UTF-8 bez BOM
        $this->datum = $s[0];
        $this->autor = $s[1];
        $this->ime = $fajl;
        #upitno: šta ako je naslov "Stigao Enil u Sarajevo"? :(
        $this->naslov = $s[2]; $this->naslov = strtolower($this->naslov);
        $this->naslov[0] = strtoupper($this->naslov[0]);
        $this->slika = $s[3];
        $d = false;
        for ($i = 4; $i < count ($s); ++$i)
        {
            if (trim($s[$i]) !== "--") {
                if (!$d)
                    $this->kratki .= trim($s[$i]) . PHP_EOL;
                else
                    $this->dugi .= trim($s[$i]) . PHP_EOL;
            }
            else $d = true;
        }
    }
    #čivamo i ime fajla ako se proslijedi kao parametar...
    function  ImeFajla ()
    {
        return trim ($this->ime);
    }
    function Autor ()
    {
        return rtrim ($this->autor);
    }
    function DatumString()
    {
        return rtrim($this->datum);
    }
    function DatumUNIX()
    {
        list($datum, $vrijeme) = explode(" ", $this->DatumString());
        list ($dat, $mj, $god) = explode (".", rtrim ($datum, "."));
        list ($sati, $minute, $sekunde) = explode (":", $vrijeme);
        return mktime (intval ($sati), intval($minute),intval($sekunde),
            intval($mj), intval($dat), intval($god));
    }
    function LinkSlike()
    {
        return rtrim($this->slika);
    }
    function ImaSlika ()
    {
        return trim($this->LinkSlike()) !== "";
    }
    function Naslov ()
    {
        return rtrim($this->naslov);
    }
    function NaslovOriginal ()
    {
        return strtoupper($this->Naslov());
    }
    function KratkiTekst ()
    {
        return trim ($this->kratki);
    }
    function DetaljnijiTekst ()
    {
        return trim($this->dugi);
    }
    function ImaDetaljnijegTeksta ()
    {
        return trim ($this->DetaljnijiTekst()) !== "";
    }
    function UkupanTekst ()
    {
        return $this->KratkiTekst() . PHP_EOL . $this->DetaljnijiTekst();
    }
    static function KriterijSortiranjaUNIX (NovostPHP $a, NovostPHP $b)
    {
        return $b->DatumUNIX() - $a->DatumUNIX();
    }

}
class NovostSQL {
    private $datum, $naslov, $autor, $kratki = "", $dugi = "", $slika, $id, $komentarisanje, $brk;
    function __construct (array $s, $broji_komentare = true)
    {
        $this->datum = $s['DATUM'];
        $this->autor = $s['AUTOR'];
        $this->id = $s['ID'];
        $this->naslov = $s['NASLOV'];# $this->naslov = strtolower($this->naslov);
        $this->slika = $s['SLIKA'];
        $this->kratki = $s['K_TEXT'];
        $this->dugi = $s['D_TEXT'];
        $this->komentarisanje = $s['KOMENTARISANJE'];
        $this->brk = $broji_komentare ? $s['BROJ_K'] : 0;;
    }
    function BrojKomentara ()
    {
        return intval ($this->brk);
    }
    function  ID ()
    {
        return trim (intval($this->id));
    }
    function Autor ()
    {
        return rtrim ($this->autor);
    }
    function DatumString()
    {
        return date("d.m.Y. (h:i)", $this->datum);
    }
    function DatumUNIX()
    {
        return $this->datum;
    }
    function LinkSlike()
    {
        return rtrim($this->slika);
    }
    function ImaSlika ()
    {
        return trim($this->LinkSlike()) !== "";
    }
    function Naslov ()
    {
        return rtrim($this->naslov);
    }
    function NaslovOriginal ()
    {
        return strtoupper($this->Naslov());
    }
    function KratkiTekst ()
    {
        return trim ($this->kratki);
    }
    function DetaljnijiTekst ()
    {
        return trim($this->dugi);
    }
    function DozvoljenoKomentarisanje ()
    {
        return boolval($this->komentarisanje);
    }
    function ImaDetaljnijegTeksta ()
    {
        return trim ($this->DetaljnijiTekst()) !== "";
    }
    function UkupanTekst ()
    {
        return $this->KratkiTekst() . PHP_EOL . $this->DetaljnijiTekst();
    }
    static function KriterijSortiranjaUNIX (NovostPHP $a, NovostPHP $b)
    {
        return $b->DatumUNIX() - $a->DatumUNIX();
    }
}
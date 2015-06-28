<?php
#require_once("Autotest.php");
class Autotest1
{
    private $atovi = array();
    private $broj = 0;
    private $tut = "", $zad ="";
    function ATovi () {return $this->atovi;}
    function BrojATova () {return count($this->atovi);}
    function __construct($tut, $zad, $broj)
    {
        $this->broj = $broj;
        $this->tut = $tut;
        $this->zad = $zad;
    }
    function DodajAT ($sta, TipAutotest $akcija, $txt)
    {
        $this->atovi[] = new AutotestDIO ($sta, $akcija, $txt, $this->BrojATova(), $this->broj);
    }
    function UkloniAT ($broj)
    {
        if ($broj >= $this->BrojATova())
            throw new DomainException ("Index za niz neispravan!");
        unset ($this->atovi[$broj]);
        $this->atovi = array_values($this->atovi);
        for ($i = 0; $i < $this->BrojATova(); ++$i)
            $this->atovi[$i]->SetDio ($i);
    }
}
?>
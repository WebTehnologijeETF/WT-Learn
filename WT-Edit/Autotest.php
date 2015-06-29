<?php
class Autotest
{
    private $id, $zid, $tid;
    private $tut = "", $zad = "", $me;
    private $code, $comm, $result;
    private $aresult1 = "",$aresult2 = "", $aresult3 = "";
    private $cs = true;
    function __construct($id, $zid, $tid, $tut, $zad, $me, $code, $comm, $result, $ar1, $ar2, $ar3, $cs)
    {
        $this->zad = $zad;
        $this->tut = $tut;
        $this->me = $me;
        $this->code = $code;
        $this->comm = $comm;
        $this->result = $result;
        $this->id = $id;
        $this->tid = $tid;
        $this->zid = $zid;
        $this->aresult1 = $ar1;
        $this->aresult2 = $ar2;
        $this->aresult3 = $ar3;
        $this->cs = $cs;
    }
    function GetID () {return $this->id;}
    function GetTutID () {return $this->tid;}
    function GetZadID () {return $this->zid;}
    function GetTitle () {return $this->me;}
    function SetTitle ($c) {$this->me = $c;}
    function GetZadTitle () {return $this->tut;}
    function GetTutTitle () {return $this->zad;}
    function GetCode () {return $this->code;}
    function SetCode ($c) {$this->code = $c;}
    function GetComment () {return $this->comm;}
    function SetComment ($c) {$this->comm = $c;}
    function GetResult () {return $this->result;}
    function SetResult ($c) {$this->result = $c;}
    function GetAltResult1 () {return $this->aresult1;}
    function SetAltResult1 ($c) {$this->aresult1 = $c;}
    function GetAltResult2 () {return $this->aresult2;}
    function SetAltResult2 ($c) {$this->aresult2 = $c;}
    function GetAltResult3 () {return $this->aresult3;}
    function SetAltResult3 ($c) {$this->aresult3 = $c;}
    function GetCaseSensitive () {return $this->cs;}
    function SetCaseSensitive ($c) {$this->cs = $c;}
}
class Zadatak
{
    private $atovi = array();
    private $id, $naziv, $tut;
    function __construct($id, $naziv, $tut)
    {
        $this->id = $id;
        $this->naziv = $naziv;
        $this->tut = $tut;
    }
    function DodajAT (Autotest $at) { $this->atovi[] = $at; }
    function BrojATova () {return count ($this->atovi);}
    function UkloniATbyID ($id)
    {
        $i = -1;
        for ($o = 0; $o < $this->BrojATova(); ++$o)
            if ($this->atovi[$o]->GetID() === $id)
                {$i = $o; break;}
        if ($i === -1)
            throw new DomainException ("Autotest sa datim ID-om ne postoji! ID: " . $id);
        unset ($this->atovi[$i]);
        $this->atovi = array_values($this->atovi);
    }
    function UkloniATbyIndex ($idx)
    {
        if ($idx >= $this->BrojATova() || $idx < 0)
            throw new DomainException ("Index nije validan! Zadatak::UkloniATbyIndex");
        unset ($this->atovi[$idx]);
        $this->atovi = array_values($this->atovi);
    }
    function ATovi () {return $this->atovi;}
    function Naziv () {return $this->naziv;}
    function GetID () {return $this->id;}
    function Tutorijal () {return $this->tut;}
}
class Tutorijal
{
    private $zadaci = array();
    private $id, $naziv, $ag;
    function __construct($id, $naziv, $ag)
    {
        $this->id = $id;
        $this->naziv = $naziv;
        $this->ag = $ag;
    }
    function DodajZadatak (Zadatak $zad) { $this->zadaci[] = $zad; }
    function BrojZadataka () {return count ($this->zadaci);}
    function UkloniZadatakbyID ($id)
    {
        $i = -1;
        for ($o = 0; $o < $this->BrojZadataka(); ++$o)
            if ($this->zadaci[$o]->GetID() === $id)
            {$i = $o; break;}
        if ($i === -1)
            throw new DomainException ("Zadatak sa datim ID-om ne postoji! ID: " . $id);
        unset ($this->zadaci[$i]);
        $this->zadaci = array_values($this->zadaci);
    }
    function UkloniZadatakbyIndex ($idx)
    {
        if ($idx >= $this->BrojZadataka() || $idx < 0)
            throw new DomainException ("Index nije validan! Tutorijal::UkloniZadatakbyIndex");
        unset ($this->zadaci[$idx]);
        $this->zadaci = array_values($this->zadaci);
    }
    function Zadaci () {return $this->zadaci;}
    function Naziv () {return $this->naziv;}
    function GetID () {return $this->id;}
    function AkademskaGodina () {return $this->ag;}
}
?>
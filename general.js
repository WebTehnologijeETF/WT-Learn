function AjaxValidiraj()
    {
        var naziv = String(document.getElementById("naziv-txt").value);
        var sifra = String(document.getElementById("sifra-txt").value);
        var ax = new XMLHttpRequest();
        ax.onreadystatechange = function()
        {
            if (ax.readyState == 4 && ax.status == 200)
            {
                var s = JSON.parse(ax.responseText);
                if (typeof s.greska === 'undefined')
                {
                    var el = document.getElementById("ajax-OK");
                    el.style.visibility = "visible";
                    el.title = "Uneseni podaci su ispravni!";
                    document.getElementById("inv-naziv").style.visibility = "collapse";
                    document.getElementById("inv-sifra").style.visibility = "collapse";
                }
                else
                {
                    document.getElementById("ajax-OK").style.visibility = "collapse";
                    var x = s["greska"].trim();
                    if (x === "Nepostojeća šifra")
                    {
                        document.getElementById("inv-naziv").style.visibility = "collapse";
                        document.getElementById("inv-sifra").style.visibility = "visible";
                        document.getElementById("inv-sifra").title = "Unijeli ste nepostojeću šifru!";
                    }
                    else if (x === "Nepostojeći predmet")
                    {
                        document.getElementById("inv-naziv").style.visibility = "visible";
                        document.getElementById("inv-naziv").children[1].innerHTML = "Nepostojeći predmet!";
                        document.getElementById("inv-naziv").title = "Unijeli ste predmet koji ne postoji!";
                        document.getElementById("inv-sifra").style.visibility = "collapse";
                    }
                    else
                    {
                        document.getElementById("inv-naziv").style.visibility = "visible";
                        document.getElementById("inv-naziv").title = "Šifra ne odgovara nazivu predmeta!";
                        document.getElementById("inv-sifra").style.visibility = "collapse";
                        document.getElementById("inv-naziv").children[1].innerHTML = "Šifra ne odgovara nazivu predmeta!";
                    }

                }
            }
            if (ax.readyState == 4 && ax.status == 404)
            {
                alert("Stranica ne postoji!")
            }
        };
        ax.open("GET", "http://zamger.etf.unsa.ba/wt/predmet_sifra.php?predmet=" + naziv + "&sifra=" + sifra, true);
        ax.send();
    }
var Proizvodi = [];
function UcitajProizvode ()
{
    var AX = new XMLHttpRequest();
    AX.onreadystatechange = function()
    {
        if (AX.readyState == 4 && AX.status == 200)
        {
            Proizvodi = JSON.parse(AX.responseText);
            document.getElementById("Data").innerHTML = KreirajTabelu (Proizvodi);
            if (Proizvodi.length === 0)
                document.getElementById("Data").innerHTML += "<br><p style='text-align: center'>Trenutno nema proizvoda. <a href='#popup-b'>Dodajte novi.</a></p>";
            var r = [];
            for (var i = 0; i < Proizvodi.length; ++i)
                r[Proizvodi[i].id] = Proizvodi[i];
            Proizvodi = r;
        }
        if (AX.readyState == 4 && AX.status == 404)
            alert("Nepostojeći proizvod!");
        if (AX.readyState == 4 && AX.status == 400)
            alert("Neispravni podaci!");
    };
    AX.open("GET", "http://zamger.etf.unsa.ba/wt/proizvodi.php?brindexa=16472", true);
    AX.send();
}
function Brisi (id)
{
    var p = confirm("Jeste li sigurni da želite obrisati sljedeći prozivod?\n\nID: " + id + "\nNaziv: " + Proizvodi[id].naziv);
    if (p == false)
        return;
    var AX = new XMLHttpRequest();
    AX.onreadystatechange = function()
    {
        if (AX.readyState == 4 && AX.status == 200)
        {
            UcitajProizvode();
        }
        if (AX.readyState == 4 && AX.status == 404)
            alert("Nepostojeći proizvod!");
        if (AX.readyState == 4 && AX.status == 400)
            alert("Neispravni podaci!");
    };
    var pi = {"id" : id};
    AX.open("POST", "http://zamger.etf.unsa.ba/wt/proizvodi.php", true);
    AX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    AX.send("brindexa=16472&proizvod=" + JSON.stringify(pi) + "&akcija=" + "brisanje");

}
var t_id = -1;
function Izmijeni (id)
{
    t_id = id;
    var p = Proizvodi[parseInt(id)];
    document.getElementById("izmjena-txt").value = p.naziv;
    document.getElementById("cijena-txt").value = p.cijena;
    document.getElementById("kolicina-txt").value = p.kolicina;
    document.getElementById("opis-txt").value = p.opis;
    document.getElementById("popup-n").innerHTML = "Izmijenite prozivod (ID: " + t_id + ")";
}
window.onkeydown = function (e)
{
    if (e.keyCode == 27) //ESC
        if (getComputedStyle(document.getElementById("popup-b"), null).getPropertyValue ("visibility") === "visible")
            window.location.href = "#"; //isključi popup prozor :)
};
function Akcija ()
{
    var p = {};
    var d = document.getElementById("izmjena-txt");
    var c = document.getElementById("cijena-txt");
    var k = document.getElementById("kolicina-txt");
    var o = document.getElementById("opis-txt");
    p.naziv = d.value;
    p.cijena = c.value;
    p.kolicina = k.value;
    p.opis = o.value;
    p.id = t_id;
    var ok = true;
    if (p.naziv.trim() === "")
    {
        d.style.border = "2px solid red";
        d.title = "Morate unijeti naziv!";
        ok = false;
    }
    else
    {
        d.style.border = "initial";
        d.title = "";
    }
    if (!c.checkValidity())
    {
        c.style.border = "2px solid red";
        c.title = "Cijena nije validna";
        ok = false;
    }
    else
    {
        c.style.border = "initial";
        c.title = "";
    }
    if (!k.checkValidity())
    {
        k.style.border = "2px solid red";
        k.title = "Cijena nije validna";
        ok = false;
    }
    else
    {
        k.style.border = "initial";
        k.title = "";
    }
    if (!ok) return;
    var sta = document.getElementById("select-akcija").value;
    var AX = new XMLHttpRequest();
    AX.onreadystatechange = function()
    {
        if (AX.readyState == 4 && AX.status == 200)
        {
            UcitajProizvode();
            window.location.href = "#";
        }
        if (AX.readyState == 4 && AX.status == 404)
            alert("Nepostojeći proizvod!");
        if (AX.readyState == 4 && AX.status == 400)
            alert("Neispravni podaci!");
    };
    AX.open("POST", "http://zamger.etf.unsa.ba/wt/proizvodi.php", true);
    AX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    AX.send("brindexa=16472&proizvod=" + JSON.stringify(p) + "&akcija=" + sta);
}
function KreirajTabelu (sta)
{
    var s = "<table id='ajax-tabela'>";
    s += "<tr id='ajax-prvi''><td>ID</td><td>Naziv</td><td>Cijena</td><td>Količina</td><td>Opis</td><td>Akcija</td></tr>";
    for (var i = 0; i < sta.length; ++i)
    {
        var o = sta[i];
        s += "<tr><td>" + o.id +
        "</td><td class='ajax-naziv'>" + o.naziv +
        "</td><td>" + o.cijena +
        "</td><td>" + o.kolicina +
        "</td><td class='ajax-opis'>" + o.opis +
        "</td><td class='ajax-table-slika'>" +
        "<a href=\"#popup-b\" onclick=\"Izmijeni(" + parseFloat(o.id) + ")\";>" +
        "<img class='slika-edit' alt='Izmijeni/Dodaj' title='Izmijeni proizvod ili dodaj novi' src='IMG/ajax_edit_add.png'></a>" +
        "<a href=\"#\" onclick=\"Brisi(" + parseFloat(o.id) + ")\";>" +
        "<img class='slika-edit' title='Izbriši proizvod' alt='Brisi' src='IMG/ajax_delete.png'></a></td></tr>";
    }
    s += "</table>";
    return s;
}
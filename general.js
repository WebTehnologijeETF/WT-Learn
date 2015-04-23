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
function AjaxLoad (str)
    {
        var ax = new XMLHttpRequest();
        var el = document.getElementById ("main-cont");
        ax.onreadystatechange = function()
            {
                if (ax.readyState == 4 && ax.status == 200)
                {
                    console.log (ax.responseText);
                    var O = Obradi(ax.responseText);

                    document.body.className = O.class;
                    document.title = O.title;
                    el.innerHTML = O.body;
                }
                if (ax.readyState == 4 && ax.status == 404)
                {
                    el.innerHTML = "<h1>Greška prilikom učitavanja stranice - stranica ne postoji (404)</h1>";
                }
            };
        ax.open("GET", str, true);
        ax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
        ax.send();
    }
function UcitajVijest (str) {
    var ax = new XMLHttpRequest();
    var el = document.getElementById("main-cont");
    ax.onreadystatechange = function () {
        if (ax.readyState == 4 && ax.status == 200) {
            var O = Obradi(ax.responseText);
            document.body.className = O.class;
            document.title = O.title;
            el.innerHTML = O.body;
        }
        if (ax.readyState == 4 && ax.status == 404) {
            el.innerHTML = "<h1>Greška prilikom učitavanja stranice - stranica ne postoji (404)</h1>";
        }
    };
    ax.open("POST", "novosti.php", true);
    //ax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
    var fd = new FormData();
    fd.append("vijest", str);
    ax.send(fd);
}
function PrikaziKomentare (ID) {
    var ax = new XMLHttpRequest();
    var el = document.getElementById("komentar-" + String (ID));
    if (el.innerHTML.trim() !== "")
    {
        el.innerHTML = "";
        return;
    }
    var novost = el.parentNode;
    ax.onreadystatechange = function () {
        if (ax.readyState == 4 && ax.status == 200) {
            var xx = ax.responseText;
            console.log("INLINE: \n" + xx);

            el.innerHTML = ObradiSPA(xx);
        }
        if (ax.readyState == 4 && ax.status == 404) {
            el.innerHTML = "<h1>Greška prilikom učitavanja stranice - stranica ne postoji (404)</h1>";
        }
    };
    ax.open("POST", "novosti.php", true);
    //ax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
    var fd = new FormData();
    fd.append("vijest", ID);
    fd.append("inline", "true");
    ax.send(fd);
}
function UcitajVijestSQL (ID) {
    var ax = new XMLHttpRequest();
    var el = document.getElementById("main-cont");
    ax.onreadystatechange = function () {
        if (ax.readyState == 4 && ax.status == 200) {
            console.log(ax.responseText);
            var O = Obradi(ax.responseText);
            document.body.className = O.class;
            document.title = O.title;
            el.innerHTML = O.body;
        }
        if (ax.readyState == 4 && ax.status == 404) {
            el.innerHTML = "<h1>Greška prilikom učitavanja stranice - stranica ne postoji (404)</h1>";
        }
    };
    ax.open("POST", "novosti.php", true);
    //ax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
    var fd = new FormData();
    fd.append("vijest", ID);
    ax.send(fd);
}
function ObradiSPA (txt)
{
    txt = String(txt);
    return /<!\-\- %Start_Komentari% \-\->(.*?[\s\S]*?)<!\-\- %End_Komentari% \-\->/img.exec(txt)[0];

}
function Obradi (txt)
    {
        txt = String(txt);
        var naslov = txt.match(/<head.*?>.*?[\s\S]*<title>(.+?|[\s\S]*?)<\/title>.*?[\s\S]*<\/head>/im);
        var bclass = /<body class\s*?=\s*?\"(.+?)\"\s*?>/igm.exec(txt);
        var body = /<body.*?[\s\S]*?>(.*?[\s\S]*?)<\/body>/img.exec(txt);
        return {
            "title" : naslov[1],
            "class" : bclass[1],
            "body" : body[1]
        };
    }


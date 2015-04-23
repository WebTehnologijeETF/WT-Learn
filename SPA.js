function AjaxLoad (str)
    {
        var ax = new XMLHttpRequest();
        var el = document.getElementById ("main-cont");
        ax.onreadystatechange = function()
            {
                if (ax.readyState == 4 && ax.status == 200)
                {
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
        ax.send();
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


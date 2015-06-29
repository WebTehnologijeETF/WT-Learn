window.onload = function () {
    document.addEventListener('keyup', eKeyUp, false);
    var x = document.getElementById ("ul-tabovi");
    x.children[0].addEventListener("click", function () { return FunClick (x, 0);});
    x.children[1].addEventListener("click", function () { return FunClick (x, 1);});
    x.children[2].addEventListener("click", function () { return FunClick (x, 2);});
    var el = document.getElementById ('etab');
    for (var i = 0; i < el.children.length; ++i)
        el.children[i].style.display = "none";
    el.children[0].style.display = "block";
    window.html.focus();
};
function FunClick (el, e)
    {
        for (var i = 0; i < el.children.length; ++i)
            el.children[i].className = "";
        el.children[e].className = "aktivan";
        var Y = ['html', 'css', 'js'];
        window[Y[e]].focus();
    }
function Navigiraj (e, s)
{
    --s;
    var el = document.getElementById (e);
    for (var i = 0; i < el.children.length; ++i)
        el.children[i].style.display = "none";
    el.children[s].style.display = "block";
    el.children[s].children[1].focus();
    //el.children[s].children[1].resize(true);
    return false;
}
function Submituj ()
{
    var frm = document.getElementById("forma-snimi");
    var h = frm['html'];
    var c = frm['css'];
    var j = frm['js'];
    h.value = window.html.getSession().getValue();
    c.value = window.css.getSession().getValue();
    j.value = window.js.getSession().getValue();
    frm.submit();
}
function Autotestiraj (id)
{
        var h = window.html.getSession().getValue();
        var c = window.css.getSession().getValue();
        var j = window.js.getSession().getValue();
        var fd = new FormData();
        fd.append ("tryit", "true");
        fd.append ("html", h);
        fd.append ("css", c);
        fd.append ("js", j);
        var ijs = document.getElementById("inc-js");
        var icss = document.getElementById("inc-css");
        fd.append ("inc-js", ijs.checked ? "da" : "ne");
        fd.append ("inc-css", icss.checked ? "da" : "ne");
        var AX = new XMLHttpRequest();
        AX.open ("POST", "editor.php", true);
        AX.onreadystatechange = function ()
        {
            if (AX.readyState === 4 && AX.status === 200)
            {
                var s = AX.responseText;
                alert (s);
                var AX2 = new XMLHttpRequest();
                AX2.open ("POST", "atrun.php", true);
                var fd2 = new FormData();
                fd2.append ("atid", parseInt(id));
                fd2.append ("code", s);
                AX2.send (fd2);
                AX2.onreadystatechange = function ()
                {
                    if (AX2.readyState === 4 && AX2.status === 200)
                    {
                        alert (AX2.responseText);
                    }
                };
            }
        };
        AX.send(fd);
}
function AutotestirajZadatak (id)
{
    alert ("Not implemented! Slalo bi HTML, CSS, JS kôd serveru i ovaj ID zadatka: " + id + ", a server bi izvršio sve autotestove za taj zadatak");
}
function Validiraj (sta, htm)
{
    var x = sta.getValue();
    var fd = new FormData();
    fd.append ("validacija", htm);
    fd.append ("sta", x);
    var AX = new XMLHttpRequest();
    AX.open ("POST", "validate_1.php", true);
    var eel = document.getElementById("load-" + htm);
    eel.style.display = "inline-block";
    AX.send (fd);
    AX.onreadystatechange = function ()
    {
        if (AX.readyState === 4 && AX.status === 200)
        {
            alert (AX.responseText);
            var el = document.getElementById("cont-w3c");
            var o = JSON.parse (AX.responseText);
            if (typeof o.status === "undefined" || o.status !== "OK")
            {
                el.innerHTML = "<h2>Greška prilikom dobavljanja podataka sa servera!</h1>";
                document.location.href = "#popup-c";
                eel.style.display = "none";
                return;
            }
            console.log (o);
            var g = "";
            if (o.validan === "da")
            {
                g = "<h3 style='color: #007007; text-align: center;'>Vaš kôd je validan! Čestitamo!</h3>";
            }
            else
            {
                var greske = o.greske;

                g = "<div id='greske'><h2>Greške</h2><ol type='1'>";
                for (var i = 0; i < greske.length; ++i)
                    g += "<li>" + greske[i] + "</li>";
                g += "</ul></div><hr>";
            }
            var upozorenja = o.upozorenja;
            var u = "<div id='upozorenja'><h2>Upozorenja</h2><ol type='1'>";
            for (var i = 0; i < upozorenja.length; ++i)
                u += "<li>" + upozorenja[i] + "</li>";
            u += "</ul></div>";
            el.innerHTML = g + "\n" + u;
            eel.style.display = "none";
            document.location.href = "#popup-b";
        }
    };
}
function nodeScriptReplace(node) {
    if ( nodeScriptIs(node) === true ) {
        node.parentNode.replaceChild( nodeScriptClone(node) , node );
    }
    else {
        var i        = 0;
        var children = node.childNodes;
        while ( i < children.length ) {
            nodeScriptReplace( children[i++] );
        }
    }

    return node;
}
function nodeScriptIs(node) {
    return node.tagName === 'SCRIPT';
}
function nodeScriptClone(node){
    var script  = document.createElement("script");
    script.text = node.innerHTML;
    for( var i = node.attributes.length-1; i >= 0; i-- ) {
        script.setAttribute( node.attributes[i].name, node.attributes[i].value );
    }
    return script;
}
function Prikazi ()
{
    var h = window.html.getSession().getValue();
    var c = window.css.getSession().getValue();
    var j = window.js.getSession().getValue();
    var fd = new FormData();
    fd.append ("tryit", "true");
    fd.append ("html", h);
    fd.append ("css", c);
    fd.append ("js", j);
    var ijs = document.getElementById("inc-js");
    var icss = document.getElementById("inc-css");
    fd.append ("inc-js", ijs.checked ? "da" : "ne");
    fd.append ("inc-css", icss.checked ? "da" : "ne");
    var AX = new XMLHttpRequest();
    AX.open ("POST", "editor.php", true);
    AX.onreadystatechange = function ()
    {
        if (AX.readyState === 4 && AX.status === 200)
        {
            var s = AX.responseText;
            var w = window.open("", "Try.WT | WTLearn", "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=780, height=200, top="+(screen.height-400)+"px, left="+(screen.width-840) + "px");
            //alert (s);
            w.document.open();
            w.document.write(s);
            w.document.close();
            //nodeScriptReplace(w.document.getElementsByTagName("html")[0]);

        }
    };
    AX.send(fd);
}
function Ucitaj ()
{
    var frm = document.getElementById("forma-ucitaj");
    //frm.onsubmit = function (e)
    var fi = frm["zip_file"];
    fi.onchange = function (ee)
    {
        var f = document.getElementById("zfile").files[0];
        var fd = new FormData();
        fd.append ("zip_file", f);
        var AX = new XMLHttpRequest();
        AX.open ("POST", "upload.php", true);

       // AX.send (fd);
        AX.onreadystatechange = function ()
        {
            if (AX.readyState === 4 && AX.status === 200)
            {
                var s = AX.responseText;
                var p = JSON.parse (s);
                if (p.status !== "1")
                {
                    alert ("Uploadovanje nije OK!\n" + p.message);
                    return false;
                }
                window.html.getSession().setValue (p.html, 1);
                window.css.getSession().setValue (p.css, 1);
                window.js.getSession().setValue (p.js, 1);
                window.html.resize(true);
                window.css.resize(true);
                window.js.resize(true);
                window.js.renderer.updateFull();
                window.css.renderer.updateFull();
                window.html.renderer.updateFull();

            }
        };
        AX.send(fd);
    };
    fi.click();

    //};
    //frm.submit();
}
function eKeyUp (e)
{
    if (e.ctrlKey && e.altKey)
    {
        var Y = ['thtml', 'tcss', 'tjs'];
        var x = document.getElementById ("ul-tabovi");
        if (e.keyCode >= 49 && e.keyCode <= 51)
        {
            FunClick (x,  e.keyCode - 49);
            document.location.href = "#" +  Y[e.keyCode - 49];
            window[Y[e.keyCode - 49].substring(1)].focus();
        }
    }
}
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
    el.children[s].children[1].resize(true);
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
function Validiraj (sta)
{
    var x = sta.getValue();
    var fd = new FormData();
    fd.append ("validacija", "w3c");
    fd.append ("sta", x);
    var AX = new XMLHttpRequest();
    AX.open ("POST", "validate_1.php", true);

    AX.send (fd);
    AX.onreadystatechange = function ()
    {
        if (AX.readyState === 4 && AX.status === 200)
        {
            document.body.innerHTML = "<a href='' onclick = 'document.history.back();'>Nazad</a>"
            document.body.innerHTML += AX.responseText;
        }
    };

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

        AX.send (fd);
        AX.onreadystatechange = function ()
        {
            if (AX.readyState === 4 && AX.status === 200)
            {
                var s = AX.responseText;
                var p = JSON.parse (s);
                if (p.status !== "1")
                {
                    alert ("Uploadovanje nije OK!");
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
                window.html.render();
                window.js.render();
                window.css.render();
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
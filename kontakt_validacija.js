function AlfaNumeric (c) {return c >= 'a' && c <= 'z' || c >= 'A' && c <= 'Z' || c >= '0' && c <= '0' || c == '_';}
function ValidirajIme (el)
{
    //validiramo bez regex-a
    el = String (el);
    if (el.length < 4)
        return "Ime mora imati barem 4 alfanumerička znaka!"
    if (el.length > 16)
        return "Ime ne može imati više od 16 alfanumeričkih znakova!"
    for (var i = 0; i < el.length; ++i)
    {
        if (!AlfaNumeric(el[i]))
            return "Ime se mora sastojati samo od slova, brojeva i znaka 'donja crta' (_)";
    }
    return "";
}
function ValidirajMail (el)
{
    el = String (el);
    var r = /^[a-z]+[a-z0-9\._\-]{2,}@[a-z0-9][a-z0-9\._]+\.[a-z0-9\._]+\b$/ig;
    return r.exec(el);
}
function ValidirajFormu (frm)
{
    var ime = frm["ime"];
    var imetxt = ValidirajIme (ime.value);
    var OK = true;
    if (imetxt !== "")
    {
        OK = false;
        document.getElementById ("invalid-ime").style.visibility = "visible";
        document.getElementById ("invalid-ime").title = imetxt;
    }
    else document.getElementById ("invalid-ime").style.visibility = "collapse";
    var mail = frm["email"];
    if (!ValidirajMail (mail.value))
    {
        OK = false;
        document.getElementById ("invalid-email").style.visibility = "visible";
        document.getElementById ("invalid-email").title = "Mail nije validan! Mora biti u formatu primjer@nesto.ba";
    }
    else document.getElementById ("invalid-email").style.visibility = "collapse";
    var mailp = frm["email_potvrda"];
    if (mailp.value !== mail.value)
    {
        OK = false;
        document.getElementById ("invalid-email-p").style.visibility = "visible";
        document.getElementById ("invalid-email-p").title = "Dva unesena maila nisu ista! Ponovite unos da mailovi budu isti!";
    }
    else document.getElementById ("invalid-email-p").style.visibility = "collapse";
    var url = frm["url"];
    url.setCustomValidity("");
    if (url.value.trim() !== "" && !url.checkValidity())
    {
        OK = false;
        document.getElementById ("invalid-url").style.visibility = "visible";
        document.getElementById ("invalid-url").title = "URL nije ispravan! Molimo unesite validan URL!";
    }
    else document.getElementById ("invalid-url").style.visibility = "collapse";
    var cmnt = frm["comment"];
    if (cmnt.value.trim() === "")
    {
        OK = false;
        cmnt.style.background = "#FF6B6D";
        document.getElementById ("invalid-comment").style.visibility = "visible";
        document.getElementById ("invalid-comment").title = "Komentar polje je OBAVEZNO. Ne može biti prazno!";
    }
    else {document.getElementById ("invalid-comment").style.visibility = "collapse"; cmnt.style.background = "";}

    return OK;
}

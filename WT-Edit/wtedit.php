<!DOCTYPE html>
<html>
<head lang = "en">
    <meta charset = "UTF-8">
    <title>WT-Editor</title>
    <link rel="stylesheet" href="editor.css">
    <link rel="stylesheet" href="editor_menu.css">
    <script src="editor.js"></script>
</head>
<body>
<div id="head">
    <a href="../index.html">WT-EDIT &dashv; Editor</a>
</div>
<div id="top"></div>
<div id="main">
    <form id="forma-snimi" class="skrivena" action="editor.php" method="post">
        <input type="hidden" name="snimi" value="da">
        <input type="hidden" name="html">
        <input type="hidden" name="css">
        <input type="hidden" name="js">
    </form>
    <a id="popup-b" href="#"></a>
    <div class="popup-c">
        <div id="popup-t">
            <div class="inline-div" id="popup-n">Status validacije</div>
            <a href="#" class="x-dugme" title="Zatvori prozor"></a>
        </div>
        <div class="popup-cont">
            <!--<div class="hdr"><a href="#greske">Greške</a></div><a href="#upozorenja">Upozorenja</a></div>-->
            <div id="cont-w3c" class="w3c-cont"></div>
        </div>
    </div>
    <form id="forma-ucitaj" class="skrivena" action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" id="zfile" name="zip_file">
    </form>
    <div class="atabovi">
        <div class="tabovi">
        <ul id="ul-tabovi">
            <li class="aktivan"><a href="#" onclick="return Navigiraj('etab', 1);">HTML</a></li>
            <li><a href="#" onclick="return Navigiraj('etab', 2);">CSS</a></li>
            <li><a href="#" onclick="return Navigiraj('etab', 3);">JavaScript</a></li>
        </ul>
            </div>
        <div class="tut-menu inslie">
            <ul class="prvi-menu">
                <li>Pokreni autotest ▾
                    <?php include_once ('AT_Menu.php'); ?>
                </li>
            </ul>
        </div>
        <div class="desno">

            <a class="dugme" onclick="return Prikazi();" href="#">Prikaži</a>
            <a class="dugme" onclick="return Submituj();" href="#">Snimi</a>
            <a class="dugme" onclick="return Ucitaj();" href="#">Učitaj</a>
        </div>
        <div class="etab" id="etab">
            <div class="odvoji" id="thtml">
                <div class="nnot">
                   <div class="dugme ex" onclick="Validiraj (window.html, 'html');">
                        Validiraj
                       <img id="load-html" src="loading.gif" alt="Pričekajte..." />
                        <!--<div class="inline">-->
                            <!--<a class="dropdown" onclick="alert('bla');" href="#">&#9660;</a>-->
                            <!--<ul class="no-list">-->
                                <!--<li><a href="#">Remoras</a></li>-->
                                <!--<li><a href="#">Tilefishes</a></li>-->
                                <!--<li><a href="#">Bluefishes</a></li>-->
                                <!--<li><a href="#">Tigerfishes</a></li>-->
                            <!--</ul>-->
                        <!--</div>-->
                    </div>
                    <span class="wbox"><input type="checkbox" id="inc-js" checked>Uključi JavaScript</span>
                    <span class="wbox"><input type="checkbox" id="inc-css" checked>Uključi CSS</span>
                    <p class="inslie"><b>Note: </b>Popup prozori moraju biti omogućeni!</p>
                </div>
                <div id="html" class="ace-tab">
                </div>
            </div>

            <div class="odvoji" id="tcss">
                <div class="nnot">
                    <div class="dugme ex" onclick="Validiraj (window.css, 'css');">
                        Validiraj
                        <img id="load-css" src="loading.gif" alt="Pričekajte..." />
                    </div>
                    <p class="inslie">&nbsp;</p>
                </div>
                <div id="css" class="ace-tab">
                </div>
            </div>

            <div class="odvoji" id="tjs">
                <div class="nnot">js komande</div>
                <div id="js" class="ace-tab">
                </div>
            </div>

        </div>
    </div>
    <div>

    </div>
    <div class='footer'>Copyright &copy; 2014 -
        <script>
            document.write(new Date().getFullYear());
        </script>
    </div>
</div>
<script src="ACE/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var html = ace.edit("html");
    var css = ace.edit("css");
    var js = ace.edit("js");
    html.setTheme("ace/theme/monokai");
    html.getSession().setMode("ace/mode/html");
    css.setTheme("ace/theme/terminal");
    css.getSession().setMode("ace/mode/css");
    js.setTheme("ace/theme/xcode");
    js.getSession().setMode("ace/mode/javascript");
    js.setValue("function Hello () {alert ('Helloooooo :)');}\nvar el = document.getElementById('hello');\n" +
    "el.addEventListener ('mouseover', function() {document.body.style.background = '#' + ((1 << 24) * Math.random() | 0).toString(16);});");
    css.setValue("body {background: green;}\nh2 {\n\ttext-align: center;\n\tposition: relative;\n\tcursor: pointer;\n}\nh2:hover {\n\ttop: 3px;\n\tborder: 2px dashed black;\n}");
    var h = "<!DOCTYPE html>\n<html>\n<head lang = \"en\">\n";
    h += "\t<meta charset = \"UTF-8\">\n\t<title>Hello, world</title>\n";
    h += "\t<\!-- CSS i JS kôd uključujete chekcboxovima iznad! -->\n";
    h += "</head>\n<body>\n\t<h2 id='hello' onclick='Hello();'>Hellooo, click me, hover me</h2>\n</body>\n</html>";
    html.setValue(h);
</script>
</body>
</html>
<?php
if (isset($_REQUEST['tryit']))
{
    $js = $_REQUEST['js'];
    $css = $_REQUEST['css'];
    $html = $_REQUEST['html'];
    $icss = $_REQUEST['inc-css'] === "da" ? true : false;
    $ijs = $_REQUEST['inc-js'] === "da" ? true : false;
    if ($icss)
    {
        $i = -1;
        if (strpos ($html, "</head>" === false))
            Greska("HTML kôd nije validan!");
        $i = strpos ($html, "</head>");
        $html = substr_replace($html, "<style>" . PHP_EOL . $css . PHP_EOL . "</style>" . PHP_EOL, $i, 0);
    }
    if ($ijs)
    {
        $i = -1;
        if (strpos ($html, "</body>" === false))
            Greska("HTML kôd nije validan!");
        $i = strpos ($html, "</body>");
        $v = count (explode(PHP_EOL, (substr($html, 0, $i))));
        //$html = substr_replace($html, "<img src=\"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBR‌​AA7\" onload=\"$js\" />" . PHP_EOL, $i, 0);
        $html = substr_replace($html, "<script>" . PHP_EOL . $js . PHP_EOL . "window.onerror = function(m, u, l) { var k = $v; alert('Desila se JavaScript greška u Vašem kôdu!\\n\\nGREŠKA (en-US): ' + m + '\\nLinija: ' + parseInt(l - k));}</script>" . PHP_EOL, $i, 0);
    }
    echo $html;
    exit();
}
function Greska ($s, $izlaz = true)
{
    echo $s;
    if ($izlaz)
        exit();
}
if (isset($_REQUEST['snimi']))
{
    $html = $_REQUEST["html"];
    $css = $_REQUEST["css"];
    $js = $_REQUEST["js"];
    $var = time();
    $ime =  sys_get_temp_dir() . DIRECTORY_SEPARATOR ."__eeWT-Learn_" . $var . DIRECTORY_SEPARATOR;
    mkdir ($ime);
    #$dir = getcwd();
    #chdir ($ime);
    file_put_contents($ime . "html.html", $html);
    file_put_contents($ime . "css.css", $css);
    file_put_contents($ime . "js.js", $js);
    $zip = new ZipArchive();
    $name = $ime . "eWT-Learn_Export__" .  $var . ".zip";
    var_dump($name);
    if($zip->open($name, ZIPARCHIVE::CREATE) !== TRUE){
        die ("Neuspješno kreiranje arhive!");
    }
    $zip->addFile($ime . "html.html", "html.html");
    $zip->addFile($ime . "css.css", "css.css");
    $zip->addFile($ime . "js.js", "js.js");
    $zip->close();
    ForceDownload($name);
    #chdir ($dir);


}
function ForceDownload ($sta)
    {
        header("Content-Disposition: attachment; filename=\"" . basename($sta) . "\"");
        header("Content-Type: application/force-download");
        header("Content-Length: " . filesize($sta));
        header("Connection: close");
        ob_end_clean();
        readfile ($sta);
        #brisanje....
    }
?>
<?php
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
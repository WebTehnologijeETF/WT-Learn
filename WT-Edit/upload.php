<?php
header ("charset=utf-8");
error_reporting(E_ALL);
if($_FILES["zip_file"]["name"]) {
    $filename = $_FILES["zip_file"]["name"];
    $source = $_FILES["zip_file"]["tmp_name"];
    $type = $_FILES["zip_file"]["type"];

    $name = explode(".", $filename);
    $okay = false;
    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    foreach($accepted_types as $mime_type) {
        if($mime_type == $type) {
            $okay = true;
            break;
        }
    }

    $continue = strtolower($name[1]) == 'zip' ? true : false;
    if(!$continue || !$okay) {
        $message = "Možete uploadovati samo .zip fajl!";
    }

    $target_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;  // change this to the correct site path
    $html = "";
    $css = "";
    $js = "";

    if(move_uploaded_file($source, $target_path)) {
        $zip = new ZipArchive();
        $x = $zip->open($target_path);
        #var_dump($x);
        if ($x === true) {
            $f = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "ep__" . time();
            #var_dump($f);
            $zip->extractTo($f); // change this to the correct site path
            $zip->close();
            $html = file_get_contents($f . DIRECTORY_SEPARATOR . "html.html");
            $js = file_get_contents($f . DIRECTORY_SEPARATOR . "js.js");
            $css = file_get_contents($f . DIRECTORY_SEPARATOR . "css.css");
            unlink($target_path);
        }
        $message = "Uspješno učitan projekat";
    } else {
        $message = "Problem sa uploadom!";
    }
    $p = array ("status" => (!$message || !$okay) ? "0" : "1",
        "message" => $message,
        "html" => $html,
        "js" => $js,
        "css" => $css);
    echo json_encode($p, JSON_FORCE_OBJECT);
}
?>
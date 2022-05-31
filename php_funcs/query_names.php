<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// for search the db
//
require_once('DBSearcher.php');

// return query
//
if(
    isset($_GET["strQuery"])
    &&
    isset($_GET['strDBType'])
    ){
    // for connect to db
    //
    $dbSearcher = new DBSearcher();

    // search based on db type
    //
    switch($_GET['strDBType']){
        case "menustat":
            $arrAllTemplateData = $dbSearcher->arrQueryMenustatNames($_GET['strQuery']);

            foreach($arrAllTemplateData as $subArr){
                $tempBody = load_template_to_string($subArr,"../templates/menustat/table_entry.html");
                echo($tempBody);
            }
            break;
        case "usda_branded":
            $arrAllTemplateData = $dbSearcher->arrQueryUSDABrandedNames($_GET['strQuery']);
            foreach($arrAllTemplateData as $subArr){
                $tempBody = load_template_to_string($subArr,'../templates/usda_branded/table_entry.html');
                echo($tempBody);
            }
            break;
        case "usda_non-branded":
            $arrAllTemplateData = $dbSearcher->arrQueryUSDANonBrandedNames($_GET['strQuery']);
            foreach($arrAllTemplateData as $subArr){
                $tempBody = load_template_to_string($subArr,'../templates/usda_non_branded/table_entry.html');
                echo($tempBody);
            }
            break;
        default:
            echo('Error query_names.php: Need to choose valid strDBType');
    }
}
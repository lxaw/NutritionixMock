<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// for search the db
//
require_once('../classes/DBSearcher.php');

// for popuplate html
//
require_once('../classes/TemplateLoader.php');

// return query
//
if(
    isset($_GET["strQuery"])
    &&
    isset($_GET['strDBType'])
    &&
    isset($_GET['intPerPage'])
    ){
    // for connect to db
    //
    $dbSearcher = new DBSearcher();
    // for populate html
    //
    $templateLoader = new TemplateLoader();

    // search based on db type
    //
    switch($_GET['strDBType']){
        // go thru db types and return data
        //

        // TO DO:
        // Make constants for directories

        case "menustat":

            $arrAllTemplateData = $dbSearcher->arrQueryMenustatNames($_GET['strQuery'],$_GET['intPerPage']);

            foreach($arrAllTemplateData as $subArr){
                $tempBody = $templateLoader->strTemplateToStr($subArr,"../../templates/menustat/table_entry.html");
                echo($tempBody);
            }
            break;
        case "usda_branded":
            $arrAllTemplateData = $dbSearcher->arrQueryUSDABrandedNames($_GET['strQuery'],$_GET['intPerPage']);
            foreach($arrAllTemplateData as $subArr){
                $tempBody = $templateLoader->strTemplateToStr($subArr,'../../templates/usda_branded/table_entry.html');
                echo($tempBody);
            }
            break;
        case "usda_non-branded":
            $arrAllTemplateData = $dbSearcher->arrQueryUSDANonBrandedNames($_GET['strQuery'],$_GET['intPerPage']);
            foreach($arrAllTemplateData as $subArr){
                $tempBody = $templateLoader->strTemplateToStr($subArr,'../../templates/usda_non_branded/table_entry.html');
                echo($tempBody);
            }
            break;
        default:
            echo('Error query_names.php: Need to choose valid strDBType');
            break;
    }
}
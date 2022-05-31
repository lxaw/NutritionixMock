<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// for searching
//
require_once('DBSearcher.php');

if(
    isset($_GET['strFoodName'])
    &&
    isset($_GET['strRestaurantName'])
    &&
    isset($_GET['strDBType'])
){

    // create a new db searcher
    //
    $dbSearcher = new DBSearcher();

    // search the db for food details
    //
    switch($_GET['strDBType']){
        case "menustat":
            $arrTemplateData = $dbSearcher->arrQueryMenustatDetail($_GET['strFoodName'],$_GET['strRestaurantName']);

            // load the data into html
            //
            $tempBody = load_template_to_string($arrTemplateData,'../templates/menustat/modal_popup.html');
            // echo the html
            //
            echo($tempBody);
            break;
        case "usda_branded":
            $arrTemplateData = $dbSearcher->arrQueryUSDABrandedDetail($_GET['strFdcId']);

            // load the data into html
            //
            $tempBody = load_template_to_string($arrTemplateData,'../templates/usda_branded/modal_popup.html');
            // echo the html
            //
            echo($tempBody);
            break;
        case "usda_non-branded":
            $arrTemplateData = $dbSearcher->arrQueryUSDANonBrandedDetail($_GET['strFdcId']);

            // load the data into html
            //
            $tempBody = load_template_to_string($arrTemplateData,'../templates/usda_non_branded/modal_popup.html');
            // echo the html
            //
            echo($tempBody);
            break;
        default:
            echo('Error query_info.php: Need to choose valid strDBType');
            break;
    }
}
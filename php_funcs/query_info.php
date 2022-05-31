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
){
    // create a new db searcher
    //
    $dbSearcher = new DBSearcher();

    // search the db for food details
    //
    $arrTemplateData = $dbSearcher->arrQueryMenustatDetail($_GET['strFoodName'],$_GET['strRestaurantName']);

    // load the data into html
    //
    $tempBody = load_template_to_string($arrTemplateData,'../templates/modal_popup.html');
    // echo the html
    //
    echo($tempBody);
}
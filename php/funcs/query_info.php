<?php
// Written by Lex Whalen

// This function queries the respective databases for
// information on foods.
// Note that each database has slightly different
// fields, so not all fields can be the same here.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// for searching
//
require_once('../classes/DBSearcher.php');

// for populating html values
//
require_once('../classes/TemplateLoader.php');


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

    // create new template loader
    //
    $templateLoader = new TemplateLoader();

    // search the db for food details
    //
    switch($_GET['strDBType']){
        case "menustat":
            $arrTemplateData = $dbSearcher->arrQueryMenustatDetail($_GET['strFoodName'],$_GET['strRestaurantName']);

            // load the data into html
            //

            $strModal = $templateLoader->strPopulateMenustatModal($arrTemplateData);
            // echo the html
            //
            // return both the datatype and the templates
            //
            $arrRet = array(
                'data_type'=>$arrTemplateData['data_type'],
                // 'templates'=>$tempBody
                'modal'=>$strModal
            );

            // echo the html
            //
            echo(json_encode($arrRet));
            break;
        case "usda_branded":
            $arrTemplateData = $dbSearcher->arrQueryUSDABrandedDetail($_GET['strFdcId']);

            // load the data into html
            //

            $strModal = $templateLoader->strPopulateUsdaBrandedModal($arrTemplateData);
            // echo the html
            //
            // return both the datatype and the templates
            //
            $arrRet = array(
                'data_type'=>$arrTemplateData['data_type'],
                // 'templates'=>$tempBody
                'modal'=>$strModal
            );

            // echo the html
            //
            echo(json_encode($arrRet));
            break;
            
        case "usda_non-branded":
            // likely to get more than one entry, so need to return multiple
            //
            $arrTemplateData = $dbSearcher->arrQueryUSDANonBrandedDetail($_GET['strFdcId']);

            // load the data into html
            //
            // *****************************
            // TO DO:
            // return an array of template loads
            // get select
            //

            $strModal = $templateLoader->strPopulateUsdaNonBrandedModal($arrTemplateData);

            // return both the datatype and the templates
            //
            $arrRet = array(
                'data_type'=>$arrTemplateData['data_type'],
                // 'templates'=>$tempBody
                'modal'=>$strModal
            );


            // echo the html
            //
            echo(json_encode($arrRet));
            break;
        default:
            echo('Error query_info.php: Need to choose valid strDBType');
            break;
    }
}
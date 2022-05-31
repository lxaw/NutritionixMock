<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// for connection to db
//
require_once('db_connect.php');

// for str replace if null
//
require_once('str_replace_if_null.php');

// for template to str
//
require_once('template_to_str.php');

// for get img path 
//
require_once('str_get_img_path.php');

if(
    isset($_GET['strFoodName'])
    &&
    isset($_GET['strRestaurantName'])
){
    // connect to db
    //
    $msConnect = new MySQLiConnection();

    // get food and restaurant name
    $strFoodName= $_GET['strFoodName'];
    $strRestaurantName = $_GET['strRestaurantName'];

    $stmt = $msConnect->mysqli()->prepare('
        select
            *
        from 
            menustat
        where
            description = "'.$strFoodName.'"
        and 
            restaurant = "'.$strRestaurantName.'"
    ');
    // TO DO:
    // Dont know why bind_param is not working here.
    //
    // $stmt->bind_param("ss",$strFoodName,$strRestaurantName);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    $strRestaurant = "";
    $strDescription = "";
    $strServingSize = "";
    $strServingSizeText = "";
    $strServingSizeUnit = "";

    $strNullReplacement = "Not present in db";

    $templateData = array(
        'description'=>$strFoodName,
        'restaurant'=>$strRestaurantName,
        'img_src'=>strGetImgPath($strFoodName,$strRestaurantName,kIMG_DIR)
    );
    

    // get nutrients
    //
    $boolFirstLoop =TRUE;
    foreach($data as $subArr){
        if($boolFirstLoop){
            // get servings
            //
            $strServingSize = strReplaceIfNull($subArr['serving_size'],$strNullReplacement);
            $strServingSizeUnit = strReplaceIfNull($subArr['serving_size_unit'],$strNullReplacement);
            $templateData['serving_size'] = $strServingSize;
            $templateData['serving_size_unit'] = $strServingSizeUnit;
        }
        $strNutrientName = strReplaceIfNull($subArr['nutrient_name'],$strNullReplacement);
        $strNutrientAmount = strReplaceIfNull($subArr['nutrient_amount'],$strNullReplacement);
        $templateData[$strNutrientName]= $strNutrientAmount;
    }
    // get serving sizes
    //

    $tempBody = load_template_to_string($templateData,'../templates/modal_popup.html');
    echo($tempBody);

    $stmt->close();
}
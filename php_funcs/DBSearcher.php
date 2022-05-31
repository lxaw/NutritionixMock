<?php
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

class DBSearcher{
    private $_MySQLiConnection;

    function __construct(){
        $this->_MySQLiConnection = new MySQLiConnection();
    }

    // Queries the menustat db.
    // This function is designed to act when the user 
    // has already queried the db for the name of the food.
    // Thus this is just for getting more info.
    // Inputs:
    // str for food name, str for restaurant name
    // Outputs:
    // array of data on the food
    function arrQueryMenustatDetail($strFoodName,$strRestaurantName){
        $stmt = $this->conn->mysqli()->prepare('
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

        return $templateData;

        $stmt->close();
    }
}
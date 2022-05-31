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
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
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

    // queries the menustat db for names 
    // Input:
    // str name of food
    // output
    // array of food names with their respective restaurants
    function arrQueryMenustatNames($strQuery){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

        $intLimit = 5;

        // prepare sql statement
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                menustat_query
            where
                description like
                    ?
            limit
                ?
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intLimit);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $strRestaurant = "";
        $strDescription = "";
        $strServingSize = "";
        $strServingSizeText = "";
        $strServingSizeUnit = "";
        $strImgPath = "";

        $strNullReplacement = "Not present in db";
        $intIndex = 0;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strRestaurant = strReplaceIfNull($subArr['restaurant'],$strNullReplacement);
            $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
            $strImgPath = strGetImgPath($strDescription,$strRestaurant,kIMG_DIR.'/'.kMENUSTAT_IMGS);

            $templateData = array(
                "index" =>$intIndex,
                "restaurant" => $strRestaurant,
                "description" => $strDescription,
                "img_path"=>$strImgPath,
            );
            array_push($arrAllTemplateData,$templateData);
            $intIndex += 1;
        }

        $stmt->close();

        return $arrAllTemplateData;
    }

    function arrQueryUSDABrandedNames($strQuery){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

        $intLimit = 5;

        // prepare sql statement
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                usda_branded_query 
            where
                description like
                    ?
            limit
                ?
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intLimit);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        print_r($data);

        $strRestaurant = "";
        $strDescription = "";
        $strImgPath = "";

        $strNullReplacement = "Not present in db";
        $intIndex = 0;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strRestaurant = strReplaceIfNull($subArr['brand_owner'],$strNullReplacement);
            $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
            $strImgPath = strGetImgPath($strDescription,$strRestaurant,kIMG_DIR.'/'.kUSDA_BRANDED_IMGS);

            $templateData = array(
                "index" =>$intIndex,
                "restaurant" => $strRestaurant,
                "description" => $strDescription,
                "img_path"=>$strImgPath,
            );
            array_push($arrAllTemplateData,$templateData);
            $intIndex += 1;
        }

        $stmt->close();

        return $arrAllTemplateData;
    }

    function arrQueryUSDANonBrandedNames($strQuery){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

        $intLimit = 5;

        // prepare sql statement
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                usda_non_branded_query 
            where
                description like
                    ?
            limit
                ?
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intLimit);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        print_r($data);

        $strRestaurant = "";
        $strDescription = "";
        $strImgPath = "";

        $strNullReplacement = "Not present in db";
        $intIndex = 0;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
            // to do:
            // usda_non_branded has no restaurant / brand owner info, so the
            // format for an image path is slightly different
            //
            $strImgPath = strGetImgPathNoRestaurant($strDescription,kIMG_DIR.'/'.kUSDA_NON_BRANDED_IMGS);

            $templateData = array(
                "index" =>$intIndex,
                "description" => $strDescription,
                "img_path"=>$strImgPath,
            );
            array_push($arrAllTemplateData,$templateData);
            $intIndex += 1;
        }

        $stmt->close();

        return $arrAllTemplateData;
    }
}
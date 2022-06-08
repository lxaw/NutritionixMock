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
            'img_src'=>strGetImgPath($strFoodName,$strRestaurantName,kIMG_DIR.'/'.kMENUSTAT_IMGS)
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

        $stmt->close();
        return $templateData;

    }

    // queries the menustat db for names 
    // Input:
    // str name of food
    // output
    // array of food names with their respective restaurants
    function arrQueryMenustatNames($strQuery,$intPerPage){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

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

        $stmt->bind_param("si",$strFormattedQuery,$intPerPage);
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

        $strBrandOwner= "";
        $strDescription = "";
        $strFdcId = "";

        $strImgPath = "";

        $strNullReplacement = "Not present in db";
        $intIndex = 0;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strBrandOwner= strReplaceIfNull($subArr['brand_owner'],$strNullReplacement);
            $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
            $strImgPath = strGetImgPath($strDescription,$strBrandOwner,kIMG_DIR.'/'.kUSDA_BRANDED_IMGS);
            $strFdcId= strReplaceIfNull($subArr['fdc_id'],$strNullReplacement);

            $templateData = array(
                "index" =>$intIndex,
                "brand_owner" => $strBrandOwner,
                "fdc_id"=>$strFdcId,
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

        $strRestaurant = "";
        $strDescription = "";
        $strImgPath = "";
        $strFdcId = "";

        $strNullReplacement = "Not present in db";
        $intIndex = 0;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
            $strFdcId= strReplaceIfNull($subArr['fdc_id'],$strNullReplacement);

            // to do:
            // usda_non_branded has no restaurant / brand owner info, so the
            // format for an image path is slightly different
            //
            $strImgPath = strGetImgPathNoRestaurant($strDescription,kIMG_DIR.'/'.kUSDA_NON_BRANDED_IMGS);

            $templateData = array(
                "index" =>$intIndex,
                'fdc_id'=>$strFdcId,
                "description" => $strDescription,
                "img_path"=>$strImgPath,
            );
            array_push($arrAllTemplateData,$templateData);
            $intIndex += 1;
        }

        $stmt->close();

        return $arrAllTemplateData;
    }

    // Queries the usda branded db.
    // This function is designed to act when the user 
    // has already queried the db for the name of the food.
    // Thus this is just for getting more info.
    // Inputs:
    // str for food name, str for restaurant name
    // Outputs:
    // array of data on the food
    function arrQueryUSDABrandedDetail($strFdcId){
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                usda_branded 
            where
                fdc_id = '.$strFdcId.'
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
            'fdc_id'=>$strFdcId,
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
                $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
                $strBrandOwner= strReplaceIfNull($subArr['brand_owner'],$strNullReplacement);
                $strImgSrc = strGetImgPath($strDescription,$strBrandOwner,kIMG_DIR.'/'.kUSDA_BRANDED_IMGS);

                $templateData['serving_size'] = $strServingSize;
                $templateData['serving_size_unit'] = $strServingSizeUnit;
                $templateData['description'] = $strDescription;
                $templateData['brand_owner'] = $strBrandOwner;
                $templateData['img_src'] = $strImgSrc;
            }
            // go thru the nutrients we want
            // note that there are a total of 101 nutrients, we only need about 3
            // Want carbs, fat, protein, energy
            //
            switch($subArr['nutrient_name']){
                case 'Energy':
                    $templateData['energy'] = $subArr['nutrient_amount'];
                    $templateData['energy_unit'] = $subArr['nutrient_unit'];
                    break;
                case 'Carbohydrate, by difference':
                    $templateData['carbohydrate'] = $subArr['nutrient_amount'];
                    $templateData['carbohydrate_unit'] = $subArr['nutrient_unit'];
                    break;
                case 'Total lipid (fat)':
                    $templateData['fat'] = $subArr['nutrient_amount'];
                    $templateData['fat_unit'] = $subArr['nutrient_unit'];
                    break;
                case 'Protein':
                    $templateData['protein'] = $subArr['nutrient_amount'];
                    $templateData['protein_unit'] = $subArr['nutrient_unit'];
                    break;
            }
        }
        // get serving sizes
        //
        $stmt->close();

        return $templateData;

    }

    // Queries the usda non branded db.
    // This function is designed to act when the user 
    // has already queried the db for the name of the food.
    // Thus this is just for getting more info.
    // Inputs:
    // str for food name, str for restaurant name
    // Outputs:
    // array of data on the food
    function arrQueryUSDANonBrandedDetail($strFdcId){
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                usda_non_branded 
            where
                fdc_id = '.$strFdcId.' 
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
            'fdc_id'=>$strFdcId,
        );

        // get nutrients
        //
        $boolFirstLoop =TRUE;
        foreach($data as $subArr){
            if($boolFirstLoop){
                // get servings
                //
                $strServingAmount = strReplaceIfNull($subArr['serving_amount'],$strNullReplacement);
                $strPortionModifier = strReplaceIfNull($subArr['portion_modifier'],$strNullReplacement);
                $strPortionGramWeight= strReplaceIfNull($subArr['portion_gram_weight'],$strNullReplacement);
                $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
                $strImgSrc = strGetImgPathNoRestaurant($strDescription,kIMG_DIR.'/'.kUSDA_NON_BRANDED_IMGS);

                $templateData['serving_amount'] = $strServingAmount;
                $templateData['portion_modifier'] = $strPortionModifier;
                $templateData['portion_gram_weight'] = $strPortionGramWeight;
                $templateData['description'] = $strDescription;
                $templateData['img_src'] = $strImgSrc;
            }
            // go thru the nutrients we want
            // note that there are a total of 101 nutrients, we only need about 3
            // Want carbs, fat, protein, energy
            //
            switch($subArr['nutrient_name']){
                case 'Energy':
                    if($subArr['nutrient_unit'] == "KCAL"){
                        $templateData['calories'] = $subArr['nutrient_amount'];
                        $templateData['energy_unit'] = $subArr['nutrient_unit'];
                    }
                    break;
                case 'Carbohydrate, by difference':
                    $templateData['carbohydrate'] = $subArr['nutrient_amount'];
                    $templateData['carbohydrate_unit'] = $subArr['nutrient_unit'];
                    break;
                case 'Total lipid (fat)':
                    $templateData['fat'] = $subArr['nutrient_amount'];
                    $templateData['fat_unit'] = $subArr['nutrient_unit'];
                    break;
                case 'Protein':
                    $templateData['protein'] = $subArr['nutrient_amount'];
                    $templateData['protein_unit'] = $subArr['nutrient_unit'];
                    break;
            }
        }
        // get serving sizes
        //
        $stmt->close();

        return $templateData;

    }

}
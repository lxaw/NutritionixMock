<?php
// for connection to db
//
require_once('MySQLiConnection.php');
// for str replace if null
//
require_once('../funcs/str_replace_if_null.php');

// for get img path 
//
require_once('../funcs/str_get_img_path.php');


// data types
// these are returned to javascript so that
// we can know what type of data is returned
//
define('kDataTypeUsdaNonBranded','usda_non_branded');
define('kDataTypeUsdaBranded','usda_branded');
define('kDataTypeMenustat','menustat');

class DBSearcher{
    // for connection to mysql
    //
    private $_MySQLiConnection;

    function __construct(){
        // upon construction create a mysql connection
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
                menustat_single_rows 
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
            'kcals'=>'',
            'protein'=>'',
            'carbs'=>'',
            'fat'=>'',
            'serving_size'=>'',
            'serving_size_unit'=>'',
            'img_src'=>strGetImgPath($strFoodName,$strRestaurantName,kIMG_DIR.'/'.kMENUSTAT_IMGS)
        );
        

        // should only provide one entry, thus loop only once
        //
        foreach($data as $subArr){
            $templateData['serving_size'] = strReplaceIfNull($subArr['serving_size'],$strNullReplacement);
            $templateData['serving_size_unit'] =  strReplaceIfNull($subArr['serving_size_unit'],$strNullReplacement);
            $templateData['energy_amount']= strReplaceIfNull($subArr['energy_amount'],$strNullReplacement);
            $templateData['protein_amount'] = strReplaceIfNull($subArr['protein_amount'],$strNullReplacement);
            $templateData['fat_amount'] = strReplaceIfNull($subArr['fat_amount'],$strNullReplacement);
            $templateData['carb_amount'] = strReplaceIfNull($subArr['carb_amount'],$strNullReplacement);
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

    function arrQueryUSDANonBrandedNames($strQuery,$intPerPage){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

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

        $stmt->bind_param("si",$strFormattedQuery,$intPerPage);
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
                usda_non_branded_single_rows
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


        // Note! 
        // There may be multiple entries for each serving size
        // thus we have an array of template data here
        //
        $templateData = array(
            // return the datatype to js
            'data_type'=>kDataTypeUsdaNonBranded,
            'fdc_id'=>$strFdcId,
        );
        $boolFirstLoop = TRUE;
        foreach($data as $tableEntry){
            // get the description (only need to get once)
            //
            if($boolFirstLoop){
                $templateData['description'] = strReplaceIfNull($tableEntry['description'],$strNullReplacement);
                $templateData['img_src'] = strGetImgPathNoRestaurant($templateData['description'],kIMG_DIR.'/'.kUSDA_NON_BRANDED_IMGS);
                $templateData['searchable_name'] = preg_replace('/[\W]/','-',$templateData['description']);
                $boolFirstLoop = FALSE;
            }

            // likely have multiple table entries
            //
            $arrSubEntry = array(
                'serving_size'=>strReplaceIfNull($tableEntry['serving_size'],$strNullReplacement),
                'protein_amount'=>strReplaceIfNull($tableEntry['protein_amount'],$strNullReplacement),
                'protein_unit'=>strReplaceIfNull($tableEntry['protein_unit'],$strNullReplacement),
                'energy_amount'=>strReplaceIfNull($tableEntry['energy_amount'],$strNullReplacement),
                'energy_unit'=>strReplaceIfNull($tableEntry['energy_unit'],$strNullReplacement),
                'carb_amount'=>strReplaceIfNull($tableEntry['carb_amount'],$strNullReplacement),
                'carb_unit'=>strReplaceIfNull($tableEntry['carb_unit'],$strNullReplacement),
                'fat_amount'=>strReplaceIfNull($tableEntry['fat_amount'],$strNullReplacement),
                'fat_unit'=>strReplaceIfNull($tableEntry['fat_unit'],$strNullReplacement),
                'serving_text'=>strReplaceIfNull($tableEntry['serving_text'],$strNullReplacement),
            );
            // push to template data
            //
            array_push($templateData,$arrSubEntry);
        }
        // get serving sizes
        //
        $stmt->close();

        return $templateData;

    }

}
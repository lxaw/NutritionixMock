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
    function arrQueryMenustatDetail($strId){
        $stmt = $this->_MySQLiConnection->mysqli()->prepare('
            select
                *
            from
                menustat_all
            where
                menustat_id = ?
        ');
        $stmt->bind_param("i",$strId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        // populate all of the data that doesn't change between entries
        //
        $templateData = array(
            'data_type'=>kDataTypeMenustat,
            'id'=>$strId,
            'description'=>strReplaceIfNull($data[0]['description'],kNULL_REPLACEMENT),
            'restaurant'=>strReplaceIfNull($data[0]['restaurant'],kNULL_REPLACEMENT),
            'img_src'=>strGetImgPath($data[0]['description'],$data[0]['restaurant'],kIMG_DIR.'/'.kMENUSTAT_IMGS)
        );
        // populate the data that does change between entries
        //
        foreach($data as $tableEntry){
            $arrSubEntry = array(
                'serving_size'=>strReplaceIfNull($tableEntry['serving_size'],kNULL_REPLACEMENT),
                'serving_size_unit'=>strReplaceIfNull($tableEntry['serving_size_unit'],kNULL_REPLACEMENT),
                'serving_size_text'=>strReplaceIfNull($tableEntry['serving_size_text'],kNULL_REPLACEMENT),
                'protein_amount'=>strReplaceIfNull($tableEntry['protein_amount'],kNULL_REPLACEMENT),
                'energy_amount'=>strReplaceIfNull($tableEntry['energy_amount'],kNULL_REPLACEMENT),
                'fat_amount'=>strReplaceIfNull($tableEntry['fat_amount'],kNULL_REPLACEMENT),
                'carb_amount'=>strReplaceIfNull($tableEntry['carb_amount'],kNULL_REPLACEMENT),
                'potassium_amount'=>strReplaceIfNull($tableEntry['potassium_amount'],kNULL_REPLACEMENT),
                'fiber_amount'=>strReplaceIfNull($tableEntry['fiber_amount'],kNULL_REPLACEMENT)
            );
            // push template data
            //
            array_push($templateData,$arrSubEntry);
        }

        $stmt->close();
        return $templateData;
    }

    // queries the menustat db for names 
    // Input:
    // str name of food
    // output
    // array of food names with their respective restaurants
    function arrQueryMenustatNames($strQuery,$intOffset){
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
                '.kENTRIES_PER_PAGE.'
            offset
                ?
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intOffset);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $strRestaurant = "";
        $strDescription = "";
        $strServingSize = "";
        $strServingSizeText = "";
        $strServingSizeUnit = "";
        $strImgPath = "";
        $strId = '';

        // start the index off at the offset value
        $intIndex = $intOffset;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strRestaurant = strReplaceIfNull($subArr['restaurant'],kNULL_REPLACEMENT);
            $strDescription= strReplaceIfNull($subArr['description'],kNULL_REPLACEMENT);
            $strImgPath = strGetImgPath($strDescription,$strRestaurant,kIMG_DIR.'/'.kMENUSTAT_IMGS);
            $strId = strReplaceIfNull($subArr['menustat_id'],kNULL_REPLACEMENT);

            $templateData = array(
                "index" =>$intIndex,
                "restaurant" => $strRestaurant,
                "description" => $strDescription,
                "img_path"=>$strImgPath,
                'id'=>$strId,
            );
            array_push($arrAllTemplateData,$templateData);
            $intIndex += 1;
        }

        $stmt->close();

        return $arrAllTemplateData;
    }

    function arrQueryUSDABrandedNames($strQuery,$intOffset){
        // TO DO:
        // Use smarter querying
        //
        $strFormattedQuery = '%'.$strQuery.'%';

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
                '.kENTRIES_PER_PAGE.'
            offset
                ?    
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intOffset);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $strBrandOwner= "";
        $strDescription = "";
        $strFdcId = "";

        $strImgPath = "";

        // start the index off at the offset value
        $intIndex = $intOffset;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strBrandOwner= strReplaceIfNull($subArr['brand_owner'],kNULL_REPLACEMENT);
            $strDescription= strReplaceIfNull($subArr['description'],kNULL_REPLACEMENT);
            $strImgPath = strGetImgPath($strDescription,$strBrandOwner,kIMG_DIR.'/'.kUSDA_BRANDED_IMGS);
            $strFdcId= strReplaceIfNull($subArr['fdc_id'],kNULL_REPLACEMENT);

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

    function arrQueryUSDANonBrandedNames($strQuery,$intOffset){
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
                '.kENTRIES_PER_PAGE.'
            offset
                ?
        ');

        $stmt->bind_param("si",$strFormattedQuery,$intOffset);
        $stmt->execute();
        $result =$stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $strRestaurant = "";
        $strDescription = "";
        $strImgPath = "";
        $strFdcId = "";

        // start the index at the offset value
        $intIndex = $intOffset;

        $arrAllTemplateData = array();

        foreach($data as $subArr){
            // perform checks for nulls here
            //
            $strDescription= strReplaceIfNull($subArr['description'],kNULL_REPLACEMENT);
            $strFdcId= strReplaceIfNull($subArr['fdc_id'],kNULL_REPLACEMENT);

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
                usda_branded_single_rows
            where
                fdc_id = ?
        ');
        $stmt->bind_param("i",$strFdcId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $templateData = array(
            'fdc_id'=>$strFdcId,
        );

        // Note! 
        // There may be multiple entries for each serving size
        // thus we have an array of template data here
        //
        $templateData = array(
            // return the datatype to js
            'data_type'=>kDataTypeUsdaBranded,
            'fdc_id'=>$strFdcId,
        );
        $boolFirstLoop = TRUE;
        foreach($data as $tableEntry){
            // get the description (only need to get once)
            //
            if($boolFirstLoop){
                $templateData['description'] = strReplaceIfNull($tableEntry['description'],kNULL_REPLACEMENT);
                $templateData['img_src'] = strGetImgPath($templateData['description'],$tableEntry['brand_owner'],kIMG_DIR.'/'.kUSDA_BRANDED_IMGS);
                $templateData['brand_owner']=strReplaceIfNull($tableEntry['brand_owner'],kNULL_REPLACEMENT);
                $boolFirstLoop = FALSE;
            }

            // likely have multiple table entries
            //
            $arrSubEntry = array(
                'serving_size'=>strReplaceIfNull($tableEntry['serving_size'],kNULL_REPLACEMENT),
                'serving_size_unit'=>strReplaceIfNull($tableEntry['serving_size_unit'],kNULL_REPLACEMENT),
                'protein_amount'=>strReplaceIfNull($tableEntry['protein_amount'],kNULL_REPLACEMENT),
                'protein_unit'=>strReplaceIfNull($tableEntry['protein_unit'],kNULL_REPLACEMENT),
                'energy_amount'=>strReplaceIfNull($tableEntry['energy_amount'],kNULL_REPLACEMENT),
                'energy_unit'=>strReplaceIfNull($tableEntry['energy_unit'],kNULL_REPLACEMENT),
                'carb_amount'=>strReplaceIfNull($tableEntry['carb_amount'],kNULL_REPLACEMENT),
                'carb_unit'=>strReplaceIfNull($tableEntry['carb_unit'],kNULL_REPLACEMENT),
                'fat_amount'=>strReplaceIfNull($tableEntry['fat_amount'],kNULL_REPLACEMENT),
                'fat_unit'=>strReplaceIfNull($tableEntry['fat_unit'],kNULL_REPLACEMENT),
                'potassium_amount'=>strReplaceIfNull($tableEntry['potassium_amount'],kNULL_REPLACEMENT),
                'potassium_unit'=>strReplaceIfNull($tableEntry['potassium_unit'],kNULL_REPLACEMENT),
                'fiber_amount'=>strReplaceIfNull($tableEntry['fiber_amount'],kNULL_REPLACEMENT),
                'fiber_unit'=>strReplaceIfNull($tableEntry['fiber_unit'],kNULL_REPLACEMENT),
            );
            // push to template data
            //
            array_push($templateData,$arrSubEntry);
        }


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
                fdc_id = ? 
        ');
        // TO DO:
        // Dont know why bind_param is not working here.
        //
        $stmt->bind_param("i",$strFdcId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

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
                $templateData['description'] = strReplaceIfNull($tableEntry['description'],kNULL_REPLACEMENT);
                $templateData['img_src'] = strGetImgPathNoRestaurant($templateData['description'],kIMG_DIR.'/'.kUSDA_NON_BRANDED_IMGS);
                $boolFirstLoop = FALSE;
            }

            // likely have multiple table entries
            //
            $arrSubEntry = array(
                'serving_size'=>strReplaceIfNull($tableEntry['serving_size'],kNULL_REPLACEMENT),
                'protein_amount'=>strReplaceIfNull($tableEntry['protein_amount'],kNULL_REPLACEMENT),
                'protein_unit'=>strReplaceIfNull($tableEntry['protein_unit'],kNULL_REPLACEMENT),
                'energy_amount'=>strReplaceIfNull($tableEntry['energy_amount'],kNULL_REPLACEMENT),
                'energy_unit'=>strReplaceIfNull($tableEntry['energy_unit'],kNULL_REPLACEMENT),
                'carb_amount'=>strReplaceIfNull($tableEntry['carb_amount'],kNULL_REPLACEMENT),
                'carb_unit'=>strReplaceIfNull($tableEntry['carb_unit'],kNULL_REPLACEMENT),
                'fat_amount'=>strReplaceIfNull($tableEntry['fat_amount'],kNULL_REPLACEMENT),
                'fat_unit'=>strReplaceIfNull($tableEntry['fat_unit'],kNULL_REPLACEMENT),
                'protein_amount'=>strReplaceIfNull($tableEntry['protein_amount'],kNULL_REPLACEMENT),
                'protein_unit'=>strReplaceIfNull($tableEntry['protein_unit'],kNULL_REPLACEMENT),
                'fiber_amount'=>strReplaceIfNull($tableEntry['fiber_amount'],kNULL_REPLACEMENT),
                'fiber_unit'=>strReplaceIfNull($tableEntry['fiber_unit'],kNULL_REPLACEMENT),
                'serving_text'=>strReplaceIfNull($tableEntry['serving_text'],kNULL_REPLACEMENT),
                'potassium_amount'=>strReplaceIfNull($tableEntry['potassium_amount'],kNULL_REPLACEMENT),
                'potassium_unit'=>strReplaceIfNull($tableEntry['potassium_unit'],kNULL_REPLACEMENT),
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
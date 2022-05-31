<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// for connection to db
//
require_once('db_connect.php');

// for template to str
//
require_once('template_to_str.php');

// for str replace if null
//
require_once('str_replace_if_null.php');

// for getting img path
//
require_once('str_get_img_path.php');

// return query
//
if(isset($_GET["strQuery"])){
    // connect to db  
    //
    $msConnect= new MySQLiConnection();

    $strFormattedQuery = '%'.$_GET['strQuery'].'%';

    // prepare sql statment
    //
    $stmt = $msConnect->mysqli()->prepare('
    select 
        *
    from
        menustat_query
    where
        description like
            ?
    limit
        5
    ');
    $stmt->bind_param("s",$strFormattedQuery);
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

    foreach($data as $subArr){
        // perform checks for nulls here
        //
        $strRestaurant = strReplaceIfNull($subArr['restaurant'],$strNullReplacement);
        $strDescription= strReplaceIfNull($subArr['description'],$strNullReplacement);
        $strImgPath = strGetImgPath($strDescription,$strRestaurant,kIMG_DIR);

        $templateData = array(
            "index" =>$intIndex,
            "restaurant" => $strRestaurant,
            "description" => $strDescription,
            "img_path"=>$strImgPath,
        );
        $tempBody = load_template_to_string($templateData,"../templates/table_entry.html");
        echo($tempBody);
        $intIndex += 1;
    }

    $stmt->close();

}
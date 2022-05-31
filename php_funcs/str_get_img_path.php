<?php

function strGetImgPath($strFoodName,$strRestaurantName,$strSourcePath){
    // returns NULL or image path
    // essentially we are redoing what we did in scrape.py
    // 's strSlugName(strName) function.
    // (see https://github.com/lxaw/menustat-food-db)
    //
    $strFormattedFoodName = str_replace("/","~",$strFoodName);
    $strFormattedFoodName= str_replace(" ","_",$strFormattedFoodName);

    $strFormattedRestName = str_replace("/","~",$strRestaurantName);
    $strFormattedRestName = str_replace(" ","_",$strRestaurantName);

    return 
        strtolower($strSourcePath."/".$strFormattedRestName."/".$strFormattedRestName."___".$strFormattedFoodName.".jpeg");
}

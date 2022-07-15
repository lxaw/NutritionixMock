<?php

function strGetImgPath($strFoodName,$strRestaurantName,$strSourcePath){
    // Return imgs path
    //
    $strFormattedFoodName = preg_replace('/[\W]/','',$strFoodName);

    $strFormattedRestName= preg_replace('/[\W]/','',$strRestaurantName);

    return 
        $strSourcePath."/".$strFormattedRestName."/".$strFormattedFoodName.".jpeg";
}

function strGetImgPathNoRestaurant($strFoodName,$strSourcePath){
    // for usda_no_branded
    // TO DO:
    // See if better way to do this.
    $strFormattedFoodName = preg_replace('/[\W]/','',$strFoodName);

    return 
        $strSourcePath."/".$strFormattedFoodName.".jpeg";
}

<?php

function strReplaceIfNull($strValue, $strReplacement){
    // replaces $value if NULL
    if($strValue!= NULL){
        return $strValue;
    }
    return $strReplacement;
}
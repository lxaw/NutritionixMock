<?php

function strReplaceIfNull($strValue, $strReplacement){
    // replaces $value if NULL
    if($strValue!= NULL && $strValue != 0){
        return $strValue;
    }
    return $strReplacement;
}
<?php

// usda non branded test for multiple serving sizes
//

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// for connect to db
//
require_once('db_connect.php');

// sql connection
$SQL = new MySQLiConnection();

// tested fdc id
// has two different serving types
//
$fdc_id = 167516;

$stmt = $SQL->mysqli()->prepare('
    select
        *
    from
        usda_non_branded
    where
        fdc_id = "'.$fdc_id.'"
');

$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

$lastPortionModifier = $data[0]['portion_modifier'];

// stores all entries
//
$arrEntries = array();

// stores values for a single entry
//
$arrEntry = array(
    'description'=>'',
    'serving_amount'=>'',
    'portion_gram_weight'=>'',
    'portion_modifier'=>'',
    'fdc_id'=>'',
    'nutrients'=>array(),
);
$boolFirstLoop = True;

foreach($data as $arrSubArr){
    // check if portion modifier changes
    // if change, need to start new entry
    //
    if($lastPortionModifier != $arrSubArr['portion_modifier']){
        $lastPortionModifier = $arrSubArr['portion_modifier'];
        // push entry to all entries
        //
        array_push($arrEntries,$arrEntry);

        // reset arrEntry
        //
        $arrEntry = array(
            'description'=>'',
            'serving_amount'=>'',
            'portion_gram_weight'=>'',
            'portion_modifier'=>'',
            'fdc_id'=>'',
            'nutrients'=>array(),
        );
        // reset first loop
        //
        $boolFirstLoop = True;

    }
    if($boolFirstLoop){
        // only put repeated info in arr once
        //
        $arrEntry['description']=$arrSubArr['description'];
        $arrEntry['fdc_id'] = $arrSubArr['fdc_id'];
        $arrEntry['serving_amount']=$arrSubArr['serving_amount'];
        $arrEntry['portion_gram_weight']=$arrSubArr['portion_gram_weight'];
        $arrEntry['portion_modifier'] = $arrSubArr['portion_modifier'];

        $boolFirstLoop = False;
    }
    // only thing that changes is nutrient amounts;
    //
    array_push($arrEntry['nutrients'],array(
        'nutrient_name'=>$arrSubArr['nutrient_name'],
        'nutrient_amount'=>$arrSubArr['nutrient_amount'],
        'nutrient_unit'=>$arrSubArr['nutrient_unit'],
        )
    );
}
// push last entry
//
array_push($arrEntries,$arrEntry);

foreach($arrEntries as $entry){
    echo('description: '.$entry['description'].'<br>');
    echo('fdc_id: '.$entry['fdc_id'].'<br>');
    echo('serving amount: '.$entry['serving_amount'].'<br>');
    echo('portion gram weight: '.$entry['portion_gram_weight'].'<br>');
    echo('portion modifier: '.$entry['portion_modifier'].'<br>');
    // loop thru nutrients
    //
    foreach($entry['nutrients'] as $arrNutrient){
        echo('nutrient name: '.$arrNutrient['nutrient_name'].'<br>');
        echo('nutrient amount: '.$arrNutrient['nutrient_amount'].'<br>');
        echo('nutrient unit: '.$arrNutrient['nutrient_unit'].'<br>');
    }

    echo('<br><br><br><br>');
}
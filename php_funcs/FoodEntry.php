<?php

class FoodEntry{
    private $_nutrient_arr;
    private $_name;
    private $_date;
    private $_user_id;

    function __construct($name, $nutrient_arr, $date, $user_id){
        $this->_name = $name;
        $this->_nutrient_arr = $nutrient_arr;
        $this->_date = $date;
        $this->_user_id= $user_id;
    }
}
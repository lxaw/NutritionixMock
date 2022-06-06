<?php

class Nutrient{
    // amount of nutrient
    private $_amount;
    // name of nutrient
    private $_name;
    
    // ctor
    // creates nutrient from name and amount
    //
    function __construct(string $name, float $amount){
        $this->name = $name;
        $this->amount = $amount;
    }
    public function getAmount(){
        return $this->_amount;
    }
    public function getName(){
        return $this->_name;
    }
}
<?php
namespace Calculator;

class ComplexNumber extends Number
{

    public function toString()
    {
        return "{$this->_value}i";
    }
}
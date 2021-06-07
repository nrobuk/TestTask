<?php
namespace Calculator;

class Number  implements IExpressionValue
{
    /**
     * @var bool
     */
    protected $_is_final = true;

    protected $_value =null;

    /**
     * Number constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if(is_numeric($value))
        {
            $this->_value = $value;
        }
        else{
            throw new Exception("Значение '{'$value}' должно быть числом");
        }
    }


    public function isFinal()
    {
        return $this->_is_final;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function toString()
    {
        return $this->_value;
    }
}
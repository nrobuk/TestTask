<?php


namespace Calculator;


class Expression implements IExpressionValue
{
    protected $_is_final = false;
    /**
     * @var Calc
     */
    private $_calculator;
    /**
     * @var IExpressionValue
     */
    private $_first;
    /**
     * @var IExpressionValue
     */
    private $_second;
    /**
     * @var string
     */
    private $_operator;
    const FINAL_CLASS_NAMES = ['Calculator\Number', 'Calculator\ComplexNumber'];

    /**
     * Expression constructor.
     * @param Calc $calculator
     * @param IExpressionValue $first
     * @param IExpressionValue $second
     * @param $operator
     */
    public function __construct(Calc $calculator, IExpressionValue $first, IExpressionValue $second, $operator)
    {
        if(!in_array($operator, $calculator::KNOWN_OPERATORS)){
            throw new \Exception("Неизвестный оператор: {$operator}");
        }
        $this->_calculator = $calculator;
        $this->_first = $first;
        $this->_second = $second;

        $this->_is_final = in_array($operator,[Calc::OPER_ADD,Calc::OPER_SUB]) && (get_class($first) != get_class($second)) && in_array(get_class($first),self::FINAL_CLASS_NAMES) && in_array(get_class($second),self::FINAL_CLASS_NAMES);

        $this->_operator = $operator;

    }

    public function isFinal(){
        return $this->_is_final;
    }

    /**
     * @return IExpressionValue
     * @throws \Exception
     */
    public function calc(){
        if($this->_is_final){
            return $this;
        }
        list($result,$operator) = $this->_calculator->process($this->_first, $this->_second, $this->_operator);
        $this->_operator = $operator;
        return $result;
    }

    public function toString()
    {
        //TODO данная функция нужна только для вывода результата, поэтому рекурсия для вложенных Expression не используется.
        $is_reverse = $this->_first instanceof ComplexNumber;
        $first = $is_reverse ? $this->_second : $this->_first;
        $second = $is_reverse ? $this->_first:$this->_second;
        if($is_reverse && $this->_operator == Calc::OPER_SUB){
            $this->_operator = Calc::OPER_ADD; //TODO Заплатка, надо разобраться и убрать
        }
        if($second instanceof Number && $second->getValue() < 0 && in_array($this->_operator, [Calc::OPER_ADD,Calc::OPER_SUB])){
            $this->_operator = $this->_operator == Calc::OPER_ADD ? Calc::OPER_SUB : Calc::OPER_ADD;
            $second_class = get_class($second);
            $second = new $second_class($second->getValue()*-1);
        }
        return "{$first->toString()} {$this->_operator} {$second->toString()}";
    }

    /**
     * @return Expression
     */
    public function getFirst()
    {
        return $this->_first;
    }

    /**
     * @return IExpressionValue
     */
    public function getSecond()
    {
        return $this->_second;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->_operator;
    }


}
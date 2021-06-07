<?php


namespace Calculator;


class Calc
{
    const OPER_ADD = '+';
    const OPER_SUB = '-';

    const OPER_MLT = '*';
    const OPER_DIV = '/';

    const KNOWN_OPERATORS = [self::OPER_ADD, self::OPER_SUB, self::OPER_MLT, self::OPER_DIV];

    /**
     * @param Number $first
     * @param Number $second
     * @return IExpressionValue|null
     * @throws \Exception
     */
    function Add(Number $first, Number $second)
    {

        if (get_class($first) == get_class($second)) {
            $class_name = get_class($first);
            $result = new $class_name($first->getValue() + $second->getValue());
        } else {
            $result = new Expression($this, $first, $second, self::OPER_ADD);
        }
        return $result;
    }

    /**
     * @param Number $first
     * @param Number $second
     * @return Expression|Number
     * @throws \Exception
     */
    public function Sub(Number $first, Number $second)
    {
        if (get_class($first) == get_class($second)) {
            $class_name = get_class($first);
            $result = new $class_name($first->getValue() - $second->getValue());
        } else {
            $result = new Expression($this, $first, $second, self::OPER_SUB);
        }
        return $result;
    }

    public function Mlt(Number $first, Number $second)
    {
        $class_name = 'Calculator\Number';
        $add_attr = 1;
        if (get_class($first) == get_class($second)) {
            if ($first instanceof ComplexNumber) {
                $add_attr = -1;
            }
        } else {
            $class_name = "Calculator\ComplexNumber";
        }
        $result = new $class_name($first->getValue() * $second->getValue() * $add_attr);

        return $result;
    }

    public function Div(Number $first, Number $second)
    {
        $class_name = 'Calculator\Number';
        $add_attr = 1;
        if (get_class($first) != get_class($second)) {
            if ($second instanceof ComplexNumber) {
                $add_attr = -1;
            }
            $class_name = "Calculator\ComplexNumber";
        }
        $result = new $class_name(($first->getValue() / $second->getValue()) * $add_attr);

        return $result;
    }

    public function process(IExpressionValue $first, IExpressionValue $second, $operator)
    {
        list($first, $second, $operator) = $this->optimize_expression($first, $second, $operator);

        $result = null;
        switch ($operator) {
            case Calc::OPER_ADD:
                $result = $this->Add($first, $second);
                break;
            case Calc::OPER_SUB:
                $result = $this->Sub($first, $second);
                break;
            case Calc::OPER_MLT:
                $result = $this->Mlt($first, $second);
                break;
            case Calc::OPER_DIV:
                $result = $this->Div($first, $second);
                break;
        }
        if (is_null($result)) {
            throw new \Exception('Призошла ошибка во время вычислений: результат равен null');
        }
        return [$result,$operator];
    }

    private function optimize_expression(IExpressionValue $first, IExpressionValue $second, $operator)
    {
        if (!($first->isFinal())) {
            $first = $first->calc();
        }
        if (!($second->isFinal())) {
            $second = $second->calc();
            if ($operator == self::OPER_SUB) {
                $value = new Number(-1);
                $second = (new Expression($this, $value, $second, Calc::OPER_MLT))->calc();
                $operator = self::OPER_ADD;
            }
        }
        if (($first instanceof Expression) || ($second instanceof Expression)) {
            if (in_array($operator, [self::OPER_MLT, self::OPER_DIV])) {
                list($first, $second, $operator) = $this->optimize_div_mlt_expression($first, $second, $operator);
            } else{
                list($operator, $first, $second) = $this->optimize_other_expression($first, $second, $operator);

            }
        }
        return [$first, $second, $operator];
    }

    /**
     * @param $first
     * @param $second
     * @param $operator
     * @return array
     * @throws \Exception
     */
    private function optimize_div_mlt_expression($first, $second, $operator)
    {
        if ($first instanceof Number) {
            $sec_first = $second->getFirst();
            $sec_second = $second->getSecond();
            $new_operator = $second->getOperator();
            $first = (new Expression($this, $first, $sec_first, $operator))->calc();
            $second = (new Expression($this, $first, $sec_second, $operator))->calc();
            $operator = $new_operator;
        } elseif ($second instanceof Number) {
            $fst_first = $first->getFirst();
            $fst_second = $first->getSecond();
            $new_operator = $first->getOperator();
            $first = (new Expression($this, $fst_first, $second, $operator))->calc();
            $second = (new Expression($this, $fst_second, $second, $operator))->calc();
            $operator = $new_operator;
        } else {
            $fst_first = $first->getFirst();
            $fst_second = $first->getSecond();
            $fst_new_operator = $first->getOperator();
            $sec_first = $second->getFirst();
            $sec_second = $second->getSecond();
            $sec_new_operator = $second->getOperator();
            $first = (new Expression($this, new Expression($this, $fst_first, $sec_first, $operator), new Expression($this, $fst_first, $sec_second, $operator), $sec_new_operator))->calc();
            $second = (new Expression($this, new Expression($this, $fst_second, $sec_first, $operator), new Expression($this, $fst_second, $sec_second, $operator), $fst_new_operator))->calc();
            $operator = $sec_new_operator;
        }
        return array($first, $second, $operator);
    }

    /**
     * @param $first
     * @param $second
     * @param $operator
     * @return array
     * @throws \Exception
     */
    private function optimize_other_expression($first, $second, $operator)
    {
        if ($first instanceof Number) {
            $sec_first = $second->getFirst();
            $sec_second = $second->getSecond();
            //$new_operator = $second->getOperator();
            if ($operator == Calc::OPER_SUB) {
                $sec_operator = $second->getOperator();
                $add_oper = $sec_operator == Calc::OPER_SUB ? -1 : 1;
                $sec_first = (new Expression($this, new Number(-1), $sec_first, Calc::OPER_MLT))->calc();
                $sec_second = (new Expression($this, new Number(-1 * $add_oper), $sec_second, Calc::OPER_MLT))->calc();
                $operator = Calc::OPER_ADD;
            }
            if (get_class($first) == get_class($sec_first)) {
                $first = (new Expression($this, $first, $sec_first, $operator))->calc();
                $second = $sec_second;
            } else {
                $first = (new Expression($this, $first, $sec_second, $operator))->calc();
                $second = $sec_first;
            }
            //$operator = $new_operator;
        } elseif ($second instanceof Number) {
            $fst_first = $first->getFirst();
            $fst_second = $first->getSecond();
            $new_operator = $first->getOperator();
            if (get_class($second) == get_class($fst_first)) {
                $first = (new Expression($this, $second, $fst_first, $operator))->calc();
                $second = $fst_second;
            } else {
                $first = (new Expression($this, $second, $fst_second, $operator))->calc();
                $second = $fst_first;
            }
            $operator = $new_operator;
        } else {
            $fst_first = $first->getFirst();
            $fst_second = $first->getSecond();
            $fst_new_operator = $first->getOperator();
            $sec_first = $second->getFirst();
            $sec_second = $second->getSecond();
            $sec_new_operator = $second->getOperator();
            if (get_class($fst_first) == get_class($sec_first)) {
                $first = (new Expression($this, $fst_first, $sec_first, $operator))->calc();
                $second = (new Expression($this, $fst_second, $sec_second, $operator))->calc();
                $operator = $fst_new_operator;
            } else {
                $first = (new Expression($this, $fst_first, $sec_second, $operator))->calc();
                $second = (new Expression($this, $sec_first, $fst_second, $operator))->calc();
                $operator = $sec_new_operator;
            }
        }
        return array($operator, $first, $second);
    }
}
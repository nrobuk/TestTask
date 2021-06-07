<?php

namespace Calculator;

interface IExpressionValue
{
    public function isFinal();

    public function toString();
}
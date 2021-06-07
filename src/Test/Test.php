<?php
//require_once '../vendor/autoload.php';

use Calculator\Calc;
use Calculator\ComplexNumber;
use Calculator\Expression;
use Calculator\Number;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAddNatural(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new Number(32);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(12+32,$value);
    }/**
     * @throws Exception
     */
    public function testAddHardNatural(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new Number(32);
        $third = new Number(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(12+32+44,$value);
    }   /**
     * @throws Exception
     */
    public function testSubNatural(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new Number(32);
        $expr = new Expression($calc,$first,$second,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(12-32,$value);
    }
    /**
     * @throws Exception
     */
    public function testSubHardNatural(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new Number(32);
        $third = new Number(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_SUB);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(44-(12-32),$value);
    }
    /**
     * @throws Exception
     */
    public function testMltHardNatural(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new Number(32);
        $third = new Number(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_MLT);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(44-12*32,$value);
    }
    /**
     * @throws Exception
     */
    public function testDivHardNatural(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new Number(32);
        $third = new Number(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_DIV);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(44-12/32,$value);
    }
    //Complex block
    /**
     * @throws Exception
     */
    public function testAddComplex(){
        $calc = new Calc();
        $first = new ComplexNumber(12);
        $second = new ComplexNumber(32);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(join([12+32,"i"]),$value);
    }/**
     * @throws Exception
     */
    public function testAddHardComplex(){
        $calc = new Calc();
        $first = new ComplexNumber(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(join([12+32+44,"i"]),$value);
    }
    /**
     * @throws Exception
     */
    public function testSubComplex(){
        $calc = new Calc();
        $first = new ComplexNumber(12);
        $second = new ComplexNumber(32);
        $expr = new Expression($calc,$first,$second,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(join([12-32,"i"]),$value);
    }
    /**
     * @throws Exception
     */
    public function testSubHardComplex(){
        $calc = new Calc();
        $first = new ComplexNumber(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_SUB);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals(join([44-(12-32),"i"]),$value);
    }
    /**
     * @throws Exception
     */
    public function testMltHardComplex(){
        $calc = new Calc();
        $first = new ComplexNumber(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_MLT);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("384 + 44i",$value);
    }
    /**
     * @throws Exception
     */
    public function testDivHardComplex(){
        $calc = new Calc();
        $first = new ComplexNumber(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_DIV);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("-0.375 + 44i",$value);
    }
    //Mix block
    /**
     * @throws Exception
     */
    public function testAddMix(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("12 + 32i",$value);
    }/**
     * @throws Exception
     */
    public function testAddHardMix(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("12 + 76i",$value);
    }
    /**
     * @throws Exception
     */
    public function testSubMix(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $expr = new Expression($calc,$first,$second,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("12 - 32i",$value);
    }
    /**
     * @throws Exception
     */
    public function testAddMix2(){
        $calc = new Calc();
        $first = new ComplexNumber(-6);
        $second = new Number(80);
        $expr = new Expression($calc,$first,$second,Calc::OPER_ADD);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("80 - 6i",$value);
    }
    /**
     * @throws Exception
     */
    public function testSubHardMix(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_SUB);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("-12 + 76i",$value);
    }
    /**
     * @throws Exception
     */
    public function testMltHardMix(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_MLT);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("-340i",$value);
    }
    /**
     * @throws Exception
     */
    public function testDivHardMix(){
        //(44i-(12/(32i)))
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $expr = new Expression($calc,$first,$second,Calc::OPER_DIV);
        $expr = new Expression($calc,$third,$expr,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("44.375i",$value);
    }

    /**
     * @throws Exception
     */
    public function testDivVeryHardStep1Mix(){
        $calc = new Calc();
        $first = new Number(12);
        $second = new ComplexNumber(32);
        $third = new ComplexNumber(44);
        $forth = new Number(56);
        $expr1 = new Expression($calc,$first,$second,Calc::OPER_DIV);
        $expr2 = new Expression($calc,$third,$forth,Calc::OPER_MLT);
        $expr = new Expression($calc,$expr1,$expr2,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("-2464.375i",$value);
    }
    /**
     * @throws Exception
     */
    public function testDivVeryHardStep2Mix(){
        $calc = new Calc();
        $fifth = new ComplexNumber(6);
        $sixth = new Number(80);
        $seventh = new Number(5);
        $expr3 = new Expression($calc,$fifth,$sixth,Calc::OPER_SUB);
        $expr = new Expression($calc,$seventh,$expr3,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("85 - 6i",$value);
    }
    /**
     * @throws Exception
     */
    public function testDivVeryHardStep3Mix(){
        $calc = new Calc();
        $first = new ComplexNumber(-2464.375);
        $second = new Number(85);
        $third = new ComplexNumber(6);
        $expr3 = new Expression($calc,$second,$third,Calc::OPER_SUB);
        $expr = new Expression($calc,$first,$expr3,Calc::OPER_SUB);
        $result = $expr->calc();
        $value = $result->toString();
        $this->assertEquals("-85 - 2458.375i",$value);
    }
}

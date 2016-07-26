<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */

namespace Chippyash\Test\Math\Matrix;

use Chippyash\Math\Matrix\Computation\Mul\Matrix as Mul;
use Chippyash\Math\Matrix\Formatter\AsciiNumeric;
use Chippyash\Math\Matrix\IdentityMatrix;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\ShiftMatrix;
use Chippyash\Type\Number\IntType;
use Chippyash\Type\String\StringType;
use Chippyash\Type\RequiredType;

class ShiftMatrixTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
    }

    public function testYouCanCreateAnIntegerUpperShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_UPPER), new IntType(IdentityMatrix::IDM_TYPE_INT));
        $test = new NumericMatrix([
            [0,1,0,0,0],
            [0,0,1,0,0],
            [0,0,0,1,0],
            [0,0,0,0,1],
            [0,0,0,0,0]
        ]);
        $this->assertEquals(
            $test->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT]),
            $uShift->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT])
            );
    }

    public function testYouCanCreateAnIntegerLowerShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_LOWER), new IntType(IdentityMatrix::IDM_TYPE_INT));
        $test = new NumericMatrix([
            [0,0,0,0,0],
            [1,0,0,0,0],
            [0,1,0,0,0],
            [0,0,1,0,0],
            [0,0,0,1,0]
        ]);
        $this->assertEquals(
            $test->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT]),
            $uShift->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT])
        );
    }

    public function testYouCanCreateARationalUpperShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_UPPER), new IntType(IdentityMatrix::IDM_TYPE_RATIONAL));
        $test = new NumericMatrix([
            [0,1,0,0,0],
            [0,0,1,0,0],
            [0,0,0,1,0],
            [0,0,0,0,1],
            [0,0,0,0,0]
        ]);
        $this->assertEquals(
            $test->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_RATIONAL]),
            $uShift->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_RATIONAL])
        );
    }

    public function testYouCanCreateARationalLowerShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_LOWER), new IntType(IdentityMatrix::IDM_TYPE_RATIONAL));
        $test = new NumericMatrix([
            [0,0,0,0,0],
            [1,0,0,0,0],
            [0,1,0,0,0],
            [0,0,1,0,0],
            [0,0,0,1,0]
        ]);
        $this->assertEquals(
            $test->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_RATIONAL]),
            $uShift->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_RATIONAL])
        );
    }

    public function testYouCanCreateAComplexUpperShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_UPPER), new IntType(IdentityMatrix::IDM_TYPE_COMPLEX));
        $test = new NumericMatrix([
            [0,1,0,0,0],
            [0,0,1,0,0],
            [0,0,0,1,0],
            [0,0,0,0,1],
            [0,0,0,0,0]
        ]);
        $this->assertEquals(
            $test->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_COMPLEX]),
            $uShift->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_COMPLEX])
        );
    }

    public function testYouCanCreateAComplexLowerShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_LOWER), new IntType(IdentityMatrix::IDM_TYPE_COMPLEX));
        $test = new NumericMatrix([
            [0,0,0,0,0],
            [1,0,0,0,0],
            [0,1,0,0,0],
            [0,0,1,0,0],
            [0,0,0,1,0]
        ]);
        $this->assertEquals(
            $test->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_COMPLEX]),
            $uShift->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_COMPLEX])
        );
    }
    
    public function testYouCanShiftAnotherMatrixByMultiplyingWithAShiftMatrix()
    {
        $uShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_UPPER), new IntType(IdentityMatrix::IDM_TYPE_INT));
        $mA = new NumericMatrix([
            [1,2,3,4,5],
            [6,7,8,9,10],
            [11,12,13,14,15],
            [16,17,18,19,20],
            [21,22,23,24,25]
        ]);
        $uTest = new NumericMatrix([
            [0,1,2,3,4],
            [0,6,7,8,9],
            [0,11,12,13,14],
            [0,16,17,18,19],
            [0,21,22,23,24]
        ]);
        $mB = $mA('Mul\Matrix', $uShift);
        $this->assertEquals(
            $uTest->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT]),
            $mB->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT])
        );

        $lShift = new ShiftMatrix(new IntType(5), new StringType(ShiftMatrix::SM_TYPE_LOWER), new IntType(IdentityMatrix::IDM_TYPE_INT));
        $lTest = new NumericMatrix([
            [2,3,4,5,0],
            [7,8,9,10,0],
            [12,13,14,15,0],
            [17,18,19,20,0],
            [22,23,24,25,0]
        ]);
        $mB = $mA('Mul\Matrix', $lShift);
        $this->assertEquals(
            $lTest->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT]),
            $mB->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT])
        );

        $uTest2 = new NumericMatrix([
            [6,7,8,9,10],
            [11,12,13,14,15],
            [16,17,18,19,20],
            [21,22,23,24,25],
            [0,0,0,0,0]
        ]);
        $mB = $uShift('Mul\Matrix', $mA);
        $this->assertEquals(
            $uTest2->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT]),
            $mB->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT])
        );

        $lTest2 = new NumericMatrix([
            [0,0,0,0,0],
            [1,2,3,4,5],
            [6,7,8,9,10],
            [11,12,13,14,15],
            [16,17,18,19,20]
        ]);
        $mB = $lShift('Mul\Matrix', $mA);
        $this->assertEquals(
            $lTest2->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT]),
            $mB->setFormatter(new AsciiNumeric())->display(['outputType' => AsciiNumeric::TP_INT])
        );
    }
}

<?php

namespace chippyash\Test\Math\Matrix;

use chippyash\Math\Matrix\MatrixFactory;
use chippyash\Type\Number\Complex\ComplexTypeFactory as CF;
use chippyash\Type\Number\Rational\RationalTypeFactory as RF;
use chippyash\Type\Number\IntType;

/**
 * Unit test for MatrixFactory Class
 */
class MatrixFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateComplexMatrixWithComplexTypeEntriesReturnsComplexMatrix()
    {
        $data = [
            [CF::create(1, -3), CF::create(-4, 6),CF::create(12, 3)]
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\ComplexMatrix',
                MatrixFactory::createComplex($data));
    }

    public function testCreateComplexMatrixWithComplexStringEntriesReturnsComplexMatrix()
    {
        $data = [
            ['1-3i', '-4+6i','12+3i']
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\ComplexMatrix',
                MatrixFactory::createComplex($data));
    }

    public function testCreateComplexMatrixWithComplexArrayEntriesReturnsComplexMatrix()
    {
        $data = [
            [[1,-3], [-4,6],[12,3]]
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\ComplexMatrix',
                MatrixFactory::createComplex($data));
    }

    /**
     * @dataProvider invalidEntries
     * @expectedException chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Invalid item type for Complex Matrix
     */
    public function testCreateComplexMatrixWithInvalidEntriesThrowsException($data)
    {
        MatrixFactory::createComplex($data);
    }

    public function invalidEntries()
    {
        return [
            [[[[1,-3, 2]]]], //too many items for complex array data item
            [[[[1]]]],      //too few items for complex array data item
            [[['foo']]]      //invalid string

        ];
    }

    public function testCreateRationalMatrixWithRationalTypeEntriesReturnsRationalMatrix()
    {
        $data = [
            [RF::create(1, -3), RF::create(-4, 6),RF::create(12, 3)]
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\RationalMatrix',
                MatrixFactory::createRational($data));
    }

    public function testCreateRationalMatrixWithRationalStringEntriesReturnsRationalMatrix()
    {
        $data = [
            ['1/-3', '-4/6', '12/3']
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\RationalMatrix',
                MatrixFactory::createRational($data));
    }

    public function testCreateRationalMatrixWithRationalArrayEntriesReturnsRationalMatrix()
    {
        $data = [
            [[1,-3], [-4,6], [12,3]]
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\RationalMatrix',
                MatrixFactory::createRational($data));
    }

    public function testCreateRationalMatrixWithFloatEntriesReturnsRationalMatrix()
    {
        $data = [
            [-0.33333333, 0.6666666, 0.25]
        ];

        $this->assertInstanceOf(
                'chippyash\Math\Matrix\RationalMatrix',
                MatrixFactory::createRational($data));
    }

    /**
     * @dataProvider invalidEntries
     * @expectedException chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Invalid item type for Rational Matrix
     */
    public function testCreateRationalMatrixWithInvalidEntriesThrowsException($data)
    {
        MatrixFactory::createRational($data);
    }

    public function testCreateReturnsCorrectMatrixType()
    {
        $this->assertInstanceOf(
                'chippyash\Math\Matrix\NumericMatrix',
                MatrixFactory::create('numeric', [[]])
                );
        $this->assertInstanceOf(
                'chippyash\Math\Matrix\NumericMatrix',
                MatrixFactory::create('foo', [[]])
                );
        $this->assertInstanceOf(
                'chippyash\Math\Matrix\RationalMatrix',
                MatrixFactory::create('rational', [[]])
                );
        $this->assertInstanceOf(
                'chippyash\Math\Matrix\ComplexMatrix',
                MatrixFactory::create('complex', [[]])
                );
    }

    public function testCreateFromFunctionReturnsMatrix()
    {
        $fn = function($r, $c){return 1;};
        $mA = MatrixFactory::createFromFunction($fn, new IntType(4), new IntType(2));
        $this->assertEquals(4, $mA->rows());
        $this->assertEquals(2, $mA->columns());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $rows must be >= 1
     */
    public function testCreateFromFunctionWithRowsLessThanOneThrowsException()
    {
        $fn = function($r, $c){return 1;};
        $mA = MatrixFactory::createFromFunction($fn, new IntType(0), new IntType(2));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $cols must be >= 1
     */
    public function testCreateFromFunctionWithColsLessThanOneThrowsException()
    {
        $fn = function($r, $c){return 1;};
        $mA = MatrixFactory::createFromFunction($fn, new IntType(1), new IntType(0));
    }
    
    public function testCreateFromComplexReturnsRationalMatrix()
    {
        $c = CF::fromString('2+4i');
        $mA = MatrixFactory::createFromComplex($c);
        $test = MatrixFactory::createRational([['2/1', '-4/1'],['4/1', '2/1']]);
        $this->assertEquals($test, $mA);
    }
}

<?php
namespace chippyash\Test\Math\Matrix;
use chippyash\Math\Matrix\FunctionMatrix;
use chippyash\Type\Number\IntType;

/**
 * Unit test for FunctionMatrix Class
 */
class FunctionMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'chippyash\Math\Matrix\FunctionMatrix';

    /**
     * @var FunctionMatrix
     */
    protected $object;

    /**
     * Simple test function
     *
     * @var callable
     */
    protected $function;

    public function setUp()
    {
        $this->function = function($row, $col) {
            return $row - $col;
        };
    }

    public function testConstructProperlyGivesFunctionMatrix()
    {
        $f = $this->function;
        $this->object = new FunctionMatrix($f, new IntType(1),new IntType(1));
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructNotCallableParameterRaisesException()
    {
        $this->object = new FunctionMatrix('foo', new IntType(1),new IntType(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $rows must be >= 1
     */
    public function testConstructRowslessThanOneRaisesException()
    {
        $f = $this->function;
        $this->object = new FunctionMatrix($f, new IntType(0),new IntType(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $cols must be >= 1
     */
    public function testConstructColslessThanOneRaisesException()
    {
        $f = $this->function;
        $this->object = new FunctionMatrix($f, new IntType(1),new IntType(0));
    }

    public function testConstructGivesExpectedOutput()
    {
        $expected = [
            [new IntType(0), new IntType(-1), new IntType(-2)],
            [new IntType(1), new IntType(0), new IntType(-1)],
            [new IntType(2), new IntType(1), new IntType(0)]
            ];
        $this->object = new FunctionMatrix($this->function, new IntType(3),new IntType(3));
        $this->assertEquals($expected, $this->object->toArray());
        $this->assertTrue($this->object->is('Complete'));
        $this->assertTrue($this->object->is('Square'));
        $this->assertFalse($this->object->is('Empty'));
        $this->assertFalse($this->object->is('RowVector'));
        $this->assertFalse($this->object->is('ColumnVector'));
    }
}

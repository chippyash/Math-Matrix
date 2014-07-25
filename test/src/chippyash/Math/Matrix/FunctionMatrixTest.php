<?php
namespace chippyash\Test\Math\Matrix;
use chippyash\Math\Matrix\FunctionMatrix;

/**
 * Unit test for FunctionMatrix Class
 */
class FunctionMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'chippyash\Matrix\FunctionMatrix';

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
        $this->object = new FunctionMatrix($this->function, 1, 1);
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $function is not callable
     */
    public function testConstructNotCallableParameterRaisesException()
    {
        $this->object = new FunctionMatrix('foo', 1, 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $rows must be int >= 1
     */
    public function testConstructRowsNotIntRaisesException()
    {
        $this->object = new FunctionMatrix($this->function, 1.123, 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $rows must be int >= 1
     */
    public function testConstructRowslessThanOneRaisesException()
    {
        $this->object = new FunctionMatrix($this->function, 0, 1);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $cols must be int >= 1
     */
    public function testConstructColsNotIntRaisesException()
    {
        $this->object = new FunctionMatrix($this->function, 1, 1.123);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $cols must be int >= 1
     */
    public function testConstructColslessThanOneRaisesException()
    {
        $this->object = new FunctionMatrix($this->function, 1, 0);
    }

    public function testConstructGivesExpectedOutput()
    {
        $test = array(
            array(0, -1, -2),
            array(1, 0, -1),
            array(2, 1, 0)
            );
        $this->object = new FunctionMatrix($this->function, 3, 3);
        $this->assertEquals($test, $this->object->toArray());
        $this->assertTrue($this->object->is('Complete'));
        $this->assertTrue($this->object->is('Square'));
        $this->assertFalse($this->object->is('Empty'));
        $this->assertFalse($this->object->is('RowVector'));
        $this->assertFalse($this->object->is('ColumnVector'));
    }
}

<?php
namespace Chippyash\Test\Math\Matrix;
use Chippyash\Math\Matrix\FunctionMatrix;
use Chippyash\Type\TypeFactory;
use Chippyash\Type\RequiredType;

/**
 * Unit test for FunctionMatrix Class
 */
class FunctionMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'Chippyash\Math\Matrix\FunctionMatrix';

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
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->function = function($row, $col) {
            return $row - $col;
        };
    }

    public function testConstructProperlyGivesFunctionMatrix()
    {
        $f = $this->function;
        $this->object = new FunctionMatrix($f, TypeFactory::createInt(1),TypeFactory::createInt(1));
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructNotCallableParameterRaisesException()
    {
        $this->object = new FunctionMatrix('foo', TypeFactory::createInt(1),TypeFactory::createInt(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $rows must be >= 1
     */
    public function testConstructRowsLessThanOneRaisesException()
    {
        $f = $this->function;
        $this->object = new FunctionMatrix($f, TypeFactory::createInt(0),TypeFactory::createInt(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $cols must be >= 1
     */
    public function testConstructColsLessThanOneRaisesException()
    {
        $f = $this->function;
        $this->object = new FunctionMatrix($f, TypeFactory::createInt(1),TypeFactory::createInt(0));
    }

    public function testConstructGivesExpectedOutput()
    {
        $expected = [
            [TypeFactory::createInt(0), TypeFactory::createInt(-1), TypeFactory::createInt(-2)],
            [TypeFactory::createInt(1), TypeFactory::createInt(0), TypeFactory::createInt(-1)],
            [TypeFactory::createInt(2), TypeFactory::createInt(1), TypeFactory::createInt(0)]
            ];
        $this->object = new FunctionMatrix($this->function, TypeFactory::createInt(3),TypeFactory::createInt(3));
        $this->assertEquals($expected, $this->object->toArray());
        $this->assertTrue($this->object->is('Complete'));
        $this->assertTrue($this->object->is('Square'));
        $this->assertFalse($this->object->is('Empty'));
        $this->assertFalse($this->object->is('RowVector'));
        $this->assertFalse($this->object->is('ColumnVector'));
    }
}

<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsRowvector;
use chippyash\Math\Matrix\Matrix;

/**
 */
class IsRowvectorTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsRowvector();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsRowvector::is()
     */
    public function testEmptyMatrixIsNotARowVector()
    {
        $mA = new Matrix(array());
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsRowvector::is()
     */
    public function testSingleItemMatrixIsNotARowVector()
    {
        $mA = new Matrix(array(1));
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsRowvector::is()
     */
    public function testColumnVectorMatrixIsNotARowVector()
    {
        $mA = new Matrix(array(array(1),array(2),array(3)));
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsRowvector::is()
     */
    public function testRowVectorMatrixIsARowVector()
    {
        $mA = new Matrix(array(array(1,2,3)));
        $this->assertTrue($this->object->is($mA));
    }
}

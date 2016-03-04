<?php
namespace Chippyash\Test\Math\Matrix;
use Chippyash\Math\Matrix\ZeroMatrix;
use Chippyash\Type\TypeFactory;
use Chippyash\Type\RequiredType;

/**
 * Unit test for ZeroMatrix Class
 */
class ZeroMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'Chippyash\Math\Matrix\ZeroMatrix';

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
    }

    public function testConstructingProperlyGivesIdentityMatrix()
    {
        $zero = TypeFactory::createInt(0);
        $two = TypeFactory::createInt(2);

        $mA = new ZeroMatrix($two, $two);
        $this->assertInstanceOf(self::NSUT, $mA);
        $this->assertFalse($mA->is('empty'));
        $this->assertTrue($mA->is('square'));
        $this->assertEquals(
                [[$zero, $zero],
                 [$zero, $zero]],
                $mA->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $rows must be >= 1
     */
    public function testConstructingWithRowsLessThanOneThrowsException()
    {
        $zero = TypeFactory::createInt(0);
        $one = TypeFactory::createInt(1);

        new ZeroMatrix($zero, $one);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $cols must be >= 1
     */
    public function testConstructingWithColsLessThanOneThrowsException()
    {
        $zero = TypeFactory::createInt(0);
        $one = TypeFactory::createInt(1);

        new ZeroMatrix($one, $zero);
    }
}

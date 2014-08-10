<?php
namespace chippyash\Test\Math\Matrix;
use chippyash\Math\Matrix\ZeroMatrix;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\Complex\ComplexType;
use chippyash\Type\Number\Complex\ComplexTypeFactory;

/**
 * Unit test for ZeroMatrix Class
 */
class ZeroMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'chippyash\Math\Matrix\ZeroMatrix';

    public function testConstructProperlyGivesIdentityMatrix()
    {
        $zero = new IntType(0);
        $one = new IntType(1);
        $two = new IntType(2);

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
    public function testConstructWithRowsLessThanOneThrowsException()
    {
        $zero = new IntType(0);
        $one = new IntType(1);

        $mA = new ZeroMatrix($zero, $one);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $cols must be >= 1
     */
    public function testConstructWithColesLeassThanOneThrowsException()
    {
        $zero = new IntType(0);
        $one = new IntType(1);

        $mA = new ZeroMatrix($one, $zero);
    }
}

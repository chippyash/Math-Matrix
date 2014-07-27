<?php
namespace chippyash\Test\Math\Matrix;
use chippyash\Math\Matrix\IdentityMatrix;
use chippyash\Type\BoolType;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\Rational\RationalType;

/**
 * Unit test for IdentityMatrix Class
 */
class IdentityMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'chippyash\Math\Matrix\IdentityMatrix';

    public function testConstructProperlyGivesIdentityMatrix()
    {

        $mA = new IdentityMatrix(new IntType(2));
        $this->assertInstanceOf(self::NSUT, $mA);
        $this->assertFalse($mA->is('empty'));
        $this->assertTrue($mA->is('square'));
        $one = new IntType(1);
        $zero = new IntType(1);
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $mA->toArray());
    }

    public function testConstructRequestingRationalisationProperlyGivesIdentityMatrix()
    {

        $mA = new IdentityMatrix(new IntType(2), new BoolType(true));
        $this->assertInstanceOf(self::NSUT, $mA);
        $this->assertFalse($mA->is('empty'));
        $this->assertTrue($mA->is('square'));
        $one = new RationalType(new IntType(1), new IntType(1));
        $zero = new RationalType(new IntType(0), new IntType(1));
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $mA->toArray());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testConstructSizeNotIntTypeRaisesException()
    {
        $this->object = new IdentityMatrix(1.123);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage size must be >= 1
     */
    public function testConstructSizelessThanOneRaisesException()
    {
        $this->object = new IdentityMatrix(New IntType(0));
    }

    public function testCreateRationalIdentityReturnsRationalMatrix()
    {
        $rA = IdentityMatrix::rationalIdentity(new IntType(2));
        $this->assertInstanceOf('chippyash\Math\Matrix\RationalMatrix', $rA);
        $one = new RationalType(new IntType(1), new IntType(1));
        $zero = new RationalType(new IntType(0), new IntType(1));
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $rA->toArray());
    }
}

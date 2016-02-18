<?php
namespace chippyash\Test\Math\Matrix;
use chippyash\Math\Matrix\IdentityMatrix;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\Complex\ComplexType;
use chippyash\Type\Number\Complex\ComplexTypeFactory;

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
        $zero = new IntType(0);
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $mA->toArray());
    }

    public function testConstructRequestingRationalisationProperlyGivesIdentityMatrix()
    {

        $mA = new IdentityMatrix(new IntType(2), new IntType(IdentityMatrix::IDM_TYPE_RATIONAL));
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
    public function testConstructSizeLessThanOneRaisesException()
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

    public function testCreateComplexIdentityReturnsComplexMatrix()
    {
        $cA = IdentityMatrix::complexIdentity(new IntType(2));
        $this->assertInstanceOf('chippyash\Math\Matrix\ComplexMatrix', $cA);
        $one = ComplexTypeFactory::create(1, 0);
        $zero = ComplexTypeFactory::create(0, 0);
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $cA->toArray());
    }

    public function testNumericIdentityFactoryMethodReturnsMatrixWithIntTypes()
    {
        $mI = IdentityMatrix::numericIdentity(new IntType(2));
        $this->assertInstanceOf('chippyash\Math\Matrix\NumericMatrix', $mI);
        $one = new IntType(1);
        $zero = new IntType(0);
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $mI->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Identity type invalid
     */
    public function testConstructWithUnknownMatrixTypeThrowsException()
    {
        $mI = new IdentityMatrix(new IntType(2), new IntType(4));
    }
}

<?php
namespace Chippyash\Test\Math\Matrix;
use Chippyash\Math\Matrix\IdentityMatrix;
use Chippyash\Type\TypeFactory;
use Chippyash\Type\Number\IntType;
use Chippyash\Type\Number\Complex\ComplexTypeFactory;
use Chippyash\Type\RequiredType;

/**
 * Unit test for IdentityMatrix Class
 */
class IdentityMatrixTest extends \PHPUnit_Framework_TestCase
{
    const NSUT = 'Chippyash\Math\Matrix\IdentityMatrix';

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
    }

    public function testConstructProperlyGivesIdentityMatrix()
    {

        $mA = new IdentityMatrix(TypeFactory::createInt(2));
        $this->assertInstanceOf(self::NSUT, $mA);
        $this->assertFalse($mA->is('empty'));
        $this->assertTrue($mA->is('square'));
        $one = TypeFactory::createInt(1);
        $zero = TypeFactory::createInt(0);
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $mA->toArray());
    }

    public function testConstructRequestingRationalisationProperlyGivesIdentityMatrix()
    {

        $mA = new IdentityMatrix(TypeFactory::createInt(2), TypeFactory::createInt(IdentityMatrix::IDM_TYPE_RATIONAL));
        $this->assertInstanceOf(self::NSUT, $mA);
        $this->assertFalse($mA->is('empty'));
        $this->assertTrue($mA->is('square'));
        $one = TypeFactory::createRational(TypeFactory::createInt(1), TypeFactory::createInt(1));
        $zero = TypeFactory::createRational(TypeFactory::createInt(0), TypeFactory::createInt(1));
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $mA->toArray());
    }

    public function testConstructSizeNotIntTypeRaisesException()
    {
        if (PHP_MAJOR_VERSION < 7) {
            $this->setExpectedException('PHPUnit_Framework_Error');
            $this->object = new IdentityMatrix(1.123);
        } else {
            $this->markTestSkipped('Test incompatible with PHP 7');
        }
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
        $rA = IdentityMatrix::rationalIdentity(TypeFactory::createInt(2));
        $this->assertInstanceOf('Chippyash\Math\Matrix\RationalMatrix', $rA);
        $one = TypeFactory::createRational(TypeFactory::createInt(1), TypeFactory::createInt(1));
        $zero = TypeFactory::createRational(TypeFactory::createInt(0), TypeFactory::createInt(1));
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $rA->toArray());
    }

    public function testCreateComplexIdentityReturnsComplexMatrix()
    {
        $cA = IdentityMatrix::complexIdentity(TypeFactory::createInt(2));
        $this->assertInstanceOf('Chippyash\Math\Matrix\ComplexMatrix', $cA);
        $one = ComplexTypeFactory::create(1, 0);
        $zero = ComplexTypeFactory::create(0, 0);
        $this->assertEquals(
                [[$one, $zero],
                 [$zero, $one]],
                $cA->toArray());
    }

    public function testNumericIdentityFactoryMethodReturnsMatrixWithIntTypes()
    {
        $mI = IdentityMatrix::numericIdentity(TypeFactory::createInt(2));
        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $mI);
        $one = TypeFactory::createInt(1);
        $zero = TypeFactory::createInt(0);
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
        $mI = new IdentityMatrix(TypeFactory::createInt(2), TypeFactory::createInt(4));
    }
}

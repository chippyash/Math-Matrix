<?php
namespace Chippyash\Test\Math\Matrix\Traits;
use Chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\ComplexMatrix;
use Chippyash\Type\TypeFactory;

class stubCreateCorrectScalarType
{
    use CreateCorrectScalarType;

    public function test(NumericMatrix $mA, $scalar)
    {
        return $this->createCorrectScalarType($mA, $scalar);
    }
}

class CreateCorrectScalarTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubCreateCorrectScalarType();
    }

    /**
     * @dataProvider validScalarCombinations
     */
    public function testTraitReturnsCorrectType($mA, $scalar, $expectedClass)
    {
        $this->assertInstanceOf(
                'Chippyash\Type\Number\\' . $expectedClass,
                $this->object->test($mA, $scalar));
    }

    public function validScalarCombinations()
    {
        return [
            [new NumericMatrix([2]), 2, 'IntType'],
            [new NumericMatrix([2]), true, 'IntType'],
            [new NumericMatrix([2]), false, 'IntType'],
            [new NumericMatrix([2]), 2.5, 'Rational\RationalType'],
            [new NumericMatrix([2]), '2/5', 'Rational\RationalType'],
            [new NumericMatrix([2]), '1+2i', 'Complex\ComplexType'],
            [new NumericMatrix([2]), TypeFactory::createComplex(2), 'Complex\ComplexType'],
            [new NumericMatrix([2]), TypeFactory::createInt(2), 'IntType'],
            [new NumericMatrix([2]), TypeFactory::createFloat(2), 'FloatType'],
            [new NumericMatrix([2]), TypeFactory::createRational(2), 'Rational\RationalType'],
            [new RationalMatrix([2]), 2, 'Rational\RationalType'],
            [new RationalMatrix([2]), 2.5, 'Rational\RationalType'],
            [new RationalMatrix([2]), '2/5', 'Rational\RationalType'],
            [new RationalMatrix([2]), true, 'Rational\RationalType'],
            [new RationalMatrix([2]), false, 'Rational\RationalType'],
            [new RationalMatrix([2]), TypeFactory::createComplex(2), 'Rational\RationalType'],
            [new RationalMatrix([2]), TypeFactory::createInt(2), 'Rational\RationalType'],
            [new RationalMatrix([2]), TypeFactory::createFloat(2), 'Rational\RationalType'],
            [new RationalMatrix([2]), TypeFactory::createRational(2), 'Rational\RationalType'],
            [new ComplexMatrix(['2+1i']), 2, 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), 2.5, 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), true, 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), false, 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), '2/5', 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), '1+2i', 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), TypeFactory::createComplex(2), 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), TypeFactory::createInt(2), 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), TypeFactory::createFloat(2), 'Complex\ComplexType'],
            [new ComplexMatrix(['2+1i']), TypeFactory::createRational(2), 'Complex\ComplexType'],
        ];
    }

    public function invalidScalarCombinations()
    {
        return [
            [new RationalMatrix([2]), '1+0i', 'Rational\RationalType'],
            [new RationalMatrix([2]), TypeFactory::createComplex(2,1), 'Rational\RationalType'],
        ];
    }
}

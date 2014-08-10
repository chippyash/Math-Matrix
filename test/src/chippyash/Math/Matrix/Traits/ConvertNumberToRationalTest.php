<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\ConvertNumberToRational;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Type\TypeFactory;

class stubConvertNumberToRational
{
    use ConvertNumberToRational;

    public function test($value)
    {
        return $this->convertNumberToRational($value);
    }
}

class ConvertNumberToRationalTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubConvertNumberToRational();
    }

    /**
     * @dataProvider validNumerics
     */
    public function testTraitReturnsCorrectType($number)
    {
        $this->assertInstanceOf(
                'chippyash\Type\Number\Rational\RationalType',
                $this->object->test($number));
    }

    public function validNumerics()
    {
        return [
            [2],
            [2.5],
            ['2/5'],
            [true],
            [false],
            ['2+0i'],
            [TypeFactory::createComplex(2)],
            [TypeFactory::createInt(2)],
            [TypeFactory::createFloat(2)],
            [TypeFactory::createRational(2)],
            [null],
        ];
    }

    /**
     * @dataProvider invalidNumerics
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     */
    public function testTraitThrowsExceptionForInvalidNumbers($number)
    {
        $this->assertInstanceOf(
                'chippyash\Type\Number\Rational\RationalType',
                $this->object->test($number));
    }

    public function invalidNumerics()
    {
        return [
            ['2+3i'],
            [new \stdClass()],
        ];
    }
}

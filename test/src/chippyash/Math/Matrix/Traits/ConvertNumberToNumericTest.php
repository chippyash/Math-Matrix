<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\ConvertNumberToNumeric;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Type\TypeFactory;
use chippyash\Type\Number\NumericTypeInterface;


class stubConvertNumberToNumeric
{
    use ConvertNumberToNumeric;

    public function test($value)
    {
        return $this->convertNumberToNumeric($value);
    }
}

class ConvertNumberToNumericTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubConvertNumberToNumeric();
    }

    /**
     * @dataProvider validNumerics
     */
    public function testTraitReturnsCorrectType($number)
    {
        $this->assertInstanceOf(
                'chippyash\Type\Number\NumericTypeInterface',
                $this->object->test($number));
    }

    public function validNumerics()
    {
        return [
            [2],
            [2.5],
            ['12.45'],
            ['2/5'],
            [true],
            [false],
            ['2+3i'],
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
                'chippyash\Type\Number\NumericTypeInterface',
                $this->object->test($number));
    }

    public function invalidNumerics()
    {
        return [
            ['foobar'],
            [new \stdClass()],
        ];
    }
}

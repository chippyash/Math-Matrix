<?php
namespace Chippyash\Test\Math\Matrix\Traits;
use Chippyash\Math\Matrix\Traits\ConvertNumberToNumeric;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\ComplexMatrix;
use Chippyash\Type\TypeFactory;
use Chippyash\Type\Interfaces\NumericTypeInterface;


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
                'Chippyash\Type\Interfaces\NumericTypeInterface',
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
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     */
    public function testTraitThrowsExceptionForInvalidNumbers($number)
    {
        $this->assertInstanceOf(
                'Chippyash\Type\Interfaces\NumericTypeInterface',
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

<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Type\TypeFactory;

class stubCreateCorrectMatrixType
{
    use CreateCorrectMatrixType;

    public function test(NumericMatrix $mA, $scalar)
    {
        return $this->createCorrectMatrixType($mA, $scalar);
    }
}

class CreateCorrectMatrixTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubCreateCorrectMatrixType();
    }

    /**
     * @dataProvider matrixTypes
     */
    public function testTraitReturnsCorrectType($mA)
    {
        $class = get_class($mA);
        $data = [[2,3]];
        $this->assertInstanceOf($class,
                $this->object->test($mA, $data));
    }

    public function matrixTypes()
    {
        return [
            [new NumericMatrix([2])],
            [new RationalMatrix([2])],
            [new ComplexMatrix(['2+1i'])],
        ];
    }
}

<?php
namespace Chippyash\Test\Math\Matrix\Traits;
use Chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use Chippyash\Matrix\Matrix;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\ComplexMatrix;

class stubTraitAssertMatrixIsNumeric
{
    use AssertMatrixIsNumeric;

    public function test(Matrix $mA, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertMatrixIsNumeric($mA)
                : $this->assertMatrixIsNumeric($mA, $msg);
    }
}

class AssertMatrixIsNumericTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitAssertMatrixIsNumeric();
    }

    public function testNumericMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'Chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNumeric',
                $this->object->test(new NumericMatrix([[19]])));
        $this->assertInstanceOf(
                'Chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNumeric',
                $this->object->test(new RationalMatrix([[19]])));
        $this->assertInstanceOf(
                'Chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNumeric',
                $this->object->test(new ComplexMatrix([[19]])));
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not numeric
     */
    public function testNonNumericMatrixThrowsException()
    {
        $this->object->test(new Matrix([0]));
    }
}

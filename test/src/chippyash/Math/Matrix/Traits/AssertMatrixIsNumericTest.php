<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;

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
                'chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNumeric',
                $this->object->test(new NumericMatrix([[19]])));
        $this->assertInstanceOf(
                'chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNumeric',
                $this->object->test(new RationalMatrix([[19]])));
        $this->assertInstanceOf(
                'chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNumeric',
                $this->object->test(new ComplexMatrix([[19]])));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not numeric
     */
    public function testNonNumericMatrixThrowsException()
    {
        $this->object->test(new Matrix([0]));
    }
}

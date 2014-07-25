<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Math\Matrix\Matrix;

class stubTraitAssertMatrixIsRational
{
    use AssertMatrixIsRational;

    public function test(Matrix $mA, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertMatrixIsRational($mA)
                : $this->assertMatrixIsRational($mA, $msg);
    }
}

class AssertMatrixIsRationalTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $mRational;
    protected $mIrationalA;
    protected $mIrationalString;

    protected function setUp()
    {
        $this->object = new stubTraitAssertMatrixIsRational();
        $this->mRational = new Matrix([[1,'2/3',3.786]]);
        $this->mIrationalA = new Matrix([[1]], false, false, null, false);
        $this->mIrationalString = new Matrix([['foo']]);
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testRationalMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertMatrixIsRational',
                $this->object->test($this->mRational));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not rational
     * @covers chippyash\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testNonRationalMatrixThrowsException()
    {
        $this->object->test($this->mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testNonRationalMatrixThrowsExceptionWithUserMessage()
    {
        $this->object->test($this->mB, 'foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not rational
     * @covers chippyash\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testEmptyMatrixThrowsException()
    {
        $this->object->test($this->mC);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not rational
     * @covers chippyash\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testSingleItemMatrixThrowsException()
    {
        $this->object->test($this->mD);
    }
}

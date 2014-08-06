<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\MatrixFactory;

class stubTraitAssertMatrixIsRational
{
    use AssertMatrixIsRational;

    public function test(NumericMatrix $mA, $msg = null)
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
    protected $mNumeric;
    protected $mComplex;

    protected function setUp()
    {
        $this->object = new stubTraitAssertMatrixIsRational();
        $this->mRational = MatrixFactory::createRational([[[1,1],'2/3',3.786]]);
        $this->mNumeric = MatrixFactory::createNumeric([[1]]);
        $this->mComplex = MatrixFactory::createComplex([['1+2i', '14-4i', '4-3i']]);
    }

    /**
     * @covers chippyash\Math\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testRationalMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsRational',
                $this->object->test($this->mRational));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not rational
     * @covers chippyash\Math\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testNonRationalMatrixThrowsException()
    {
        $this->object->test($this->mNumeric);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Math\Matrix\Traits\AssertMatrixIsRational::assertMatrixIsRational
     */
    public function testNonRationalMatrixThrowsExceptionWithUserMessage()
    {
        $this->object->test($this->mComplex, 'foo');
    }

}

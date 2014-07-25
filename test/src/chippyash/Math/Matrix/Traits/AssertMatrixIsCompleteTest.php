<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Matrix;

class stubTraitAssertMatrixIsComplete
{
    use AssertMatrixIsComplete;

    public function test(Matrix $mA, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertMatrixIsComplete($mA)
                : $this->assertMatrixIsComplete($mA, $msg);
    }
}

class AssertMatrixIsCompleteTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $mA;
    protected $mB;

    protected function setUp()
    {
        $this->object = new stubTraitAssertMatrixIsComplete();
        $this->mA = new Matrix([[1,2,3],[4,5,6],[7,8,9]]);
        $this->mB = new Matrix([[1,2,3],[4,5],[7]]);
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertMatrixIsComplete::assertMatrixIsComplete
     */
    public function testCompleteMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertMatrixIsComplete',
                $this->object->test($this->mA));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     * @covers chippyash\Matrix\Traits\AssertMatrixIsComplete::assertMatrixIsComplete
     */
    public function testIncompleteMatrixThrowsException()
    {
        $this->object->test($this->mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertMatrixIsComplete::assertMatrixIsComplete
     */
    public function testIncompleteMatrixThrowsExceptionWithUserMessage()
    {
        $this->object->test($this->mB, 'foo');
    }
}

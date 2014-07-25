<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixColumnsAreEqual;
use chippyash\Math\Matrix\Matrix;

class stubTraitAssertMatrixColumnsAreEqual
{
    use AssertMatrixColumnsAreEqual;

    public function test(Matrix $mA, Matrix $mB, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertMatrixColumnsAreEqual($mA, $mB)
                : $this->assertMatrixColumnsAreEqual($mA, $mB, $msg);
    }
}

class AssertMatrixColumnsAreEqualTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $mA;
    protected $mB;

    protected function setUp()
    {
        $this->object = new stubTraitAssertMatrixColumnsAreEqual();
        $this->mA = new Matrix([[1,2,3],[4,5,6],[7,8,9]]);
        $this->mB = new Matrix([[1,2],[4,5],[7,8]]);
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual::assertMatrixColumnsAreEqual
     */
    public function testEqualColumnsReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertMatrixColumnsAreEqual',
                $this->object->test($this->mA, $this->mA));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: mA->cols != mB->cols
     * @covers chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual::assertMatrixColumnsAreEqual
     */
    public function testUnequalColumnsThrowsException()
    {
        $this->object->test($this->mA, $this->mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual::assertMatrixColumnsAreEqual
     */
    public function testUnequalColumnsThrowsExceptionWithUserMessage()
    {
        $this->object->test($this->mA, $this->mB, 'foo');
    }
}

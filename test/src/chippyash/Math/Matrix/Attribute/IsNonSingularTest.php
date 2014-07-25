<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsNonsingular;
use chippyash\Math\Matrix\Matrix;

/**
 */
class IsNonSingularTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsNonsingular();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not square
     */
    public function testEmptyMatrixThrowsException()
    {
        $this->object->is(new Matrix([]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not square
     */
    public function testSingleItemZeroMatrixThrowsException()
    {
        $this->object->is(new Matrix([0]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not square
     */
    public function testIncompleteMatrixReturnsFalse()
    {
        $this->object->is(new Matrix([[1, 2], [1]]));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsNonSingular::is()
     * @dataProvider singularMatrices
     */
    public function testSingularMatricesReturnsFalse(array $m)
    {
        $this->assertFalse($this->object->is(new Matrix($m)));
    }

    public function singularMatrices()
    {
        return [
            [[[0,0],[0,0]]],
            [[[0,0],[0,1]]],
            [[[0,0],[1,0]]],
            [[[0,0],[1,1]]],
            [[[0,1],[0,0]]],
            [[[0,1],[0,1]]],
            [[[1,0],[0,0]]],
            [[[1,0],[1,0]]],
            [[[1,1],[0,0]]],
            [[[1,1],[1,1]]],
            [[[1,2,3],[4,5,6],[7,8,9]]]
        ];
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsNonSingular::is()
     */
    public function testNonSingularMatrixReturnsTrue()
    {
        $this->assertTrue($this->object->is(new Matrix([[12,2,3],[4,5,6],[7,8,9]])));
    }
}

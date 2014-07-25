<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsComplete;
use chippyash\Math\Matrix\Matrix;

/**
 */
class IsCompleteTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsComplete();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsComplete::is()
     * @dataProvider completeArrays
     */
    public function testCompleteMatricesReturnTrue($arr)
    {
        $mA = new Matrix($arr);
        $this->assertTrue($this->object->is($mA));
    }

    /**
     *
     * @return array [[testArray], ...]
     */
    public function completeArrays()
    {
        return [
            [[]],        //shorthand empty array
            [[[]]],      //longhand empty array
            [[1]],       //shorthand single vertice array
            [[[1]]],     //longhand single vertice array
            [[[1, 2], [2, 1]]], //even number array
            [[[1, 2, 3], [3, 2, 1], [2, 1, 3]]], //odd number array
        ];
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsComplete::is()
     * @dataProvider nonCompleteArrays
     */
    public function testIncompleteMatricesReturnFalse($arr)
    {
        $mA = new Matrix($arr);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     *
     * @return array [[testArray], ...]
     */
    public function nonCompleteArrays()
    {
        return [
            [[[1], [2, 1]]], //2nd row invalid
            [[[1, 2], [2]]], //2nd row invalid
            [[[1, 2, 3], [], [3, 2, 1]]], //2nd row invalid - is empty
            [[[1, 2, 3], [3, 2, 1], [2, 1]]], //3rd row invalid
        ];
    }
}

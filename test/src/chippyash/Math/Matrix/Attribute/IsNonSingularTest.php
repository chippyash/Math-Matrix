<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsNonsingular;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\RequiredType;

/**
 */
class IsNonSingularTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new IsNonsingular();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    /**
     * @dataProvider singularMatrices
     */
    public function testSingularMatricesReturnsFalse(array $m)
    {
        $this->assertFalse($this->object->is(new NumericMatrix($m)));
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

    public function testNonSingularMatrixReturnsTrue()
    {
        $this->assertTrue($this->object->is(new NumericMatrix([[12,2,3],[4,5,6],[7,8,9]])));
        $this->assertTrue($this->object->is(new NumericMatrix([])));
        $this->assertTrue($this->object->is(new NumericMatrix([[1]])));
    }
}

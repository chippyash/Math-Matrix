<?php
namespace Chippyash\Test\Math\Matrix\Computation\Div;
use Chippyash\Math\Matrix\Computation\Div\Matrix as DM;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\ComplexMatrix;
use Chippyash\Type\RequiredType;

/**
 * Division by matrix test
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new DM();
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter is not a matrix
     */
    public function testComputeRejectsSecondParamNotBeingMatrix()
    {
        $m = new NumericMatrix([]);
        $p = 'foo';
        $this->object->compute($m, $p);
    }

    public function testComputeOnlyAcceptsMatrixValues()
    {
        $m = new NumericMatrix([1]);
        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $this->object->compute($m, $m));
    }

    public function testComputeWithAnEmptyMatrixReturnsAMatrix()
    {
        $empty = new NumericMatrix([]);
        $one = new NumericMatrix([1]);
        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $this->object->compute($empty, $one));
        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $this->object->compute($one, $empty));
        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $this->object->compute($empty, $empty));
    }

    /**
     * @dataProvider correctResults
     */
    public function testComputeReturnsCorrectResults($a, $b, $r)
    {
        $mA = new RationalMatrix($a);
        $mB = new RationalMatrix($b);
        $result = new RationalMatrix($r);
        $this->assertEquals($result, $this->object->compute($mA, $mB));
    }

    public function correctResults()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        return [
            [[[1,2,3],
             [4,5,6],
             [7,8,9]],
            [[1,2,4],
             [3,6,9],
             [-4,12,15]],
            [[0,'1/3',0],
             ['-39/20','107/60','-3/20'],
             ['-39/10','97/30','-3/10']]]
        ];
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Division by zero
     */
    public function testComputeWithZeroMatrixThrowsException()
    {
        $mA = new RationalMatrix([1]);
        $mB = new RationalMatrix([0]);
        $this->object->compute($mA, $mB);
    }
}

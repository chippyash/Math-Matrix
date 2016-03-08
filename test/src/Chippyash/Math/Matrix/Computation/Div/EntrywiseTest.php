<?php
namespace Chippyash\Test\Math\Matrix\Computation\Div;
use Chippyash\Math\Matrix\Computation\Div\Entrywise as DM;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Type\RequiredType;

/**
 * Division using Entrywise matrix test
 */
class EntrywiseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DM
     */
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new DM();
    }

    /**
     * @expectedException \Chippyash\Matrix\Exceptions\MatrixException
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
            [[-1,2,4],
             [3,6,9],
             [-4,12,-15]],
            [[-1,1,'3/4'],
             ['4/3','5/6','2/3'],
             ['-7/4','2/3','-3/5']]]
        ];
    }

    public function testDivisionByZeroResultsInZeroVertices()
    {
        $dataA = [[1,2,3]];
        $dataB = [[0,0,0]];
        $mA = new NumericMatrix($dataA);
        $mB = new NumericMatrix($dataB);
        $result = $this->object->compute($mA, $mB);
        $this->assertEquals(0, $result->get(1,1)->get());
        $this->assertEquals(0, $result->get(1,2)->get());
        $this->assertEquals(0, $result->get(1,3)->get());
    }
}

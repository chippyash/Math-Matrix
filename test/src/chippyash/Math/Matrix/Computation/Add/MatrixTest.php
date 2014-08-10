<?php
namespace chippyash\Test\Math\Matrix\Computation\Add;
use chippyash\Math\Matrix\Computation\Add\Matrix as CMatrix;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\Rational\RationalTypeFactory;

/**
 * Description of MatrixTest
 *
 * @author akitson
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new CMatrix();
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
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
        $m = new NumericMatrix([]);
        $this->assertInstanceOf('chippyash\Math\Matrix\NumericMatrix', $this->object->compute($m, $m));
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new NumericMatrix([]);
        $test = $this->object->compute($m, new NumericMatrix([]));
        $this->assertTrue($test->is('empty'));
        $test = $this->object->compute(new NumericMatrix(array(1)), $m);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage mA->cols != mB->cols
     */
    public function testComputeThrowsExceptionIfBothOperandsNotSameSize()
    {
        $mA = new NumericMatrix(array(1));
        $mB = new NumericMatrix(array(array(1,2),array(2,1)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($op1, $op2, $test, $expectedType)
    {
        $mA = new NumericMatrix($op1);
        $mB = new NumericMatrix($op2);
        $this->assertEquals(
                $this->toStrongType($test, $expectedType),
                $this->object->compute($mA, $mB)->toArray());
    }

    public function computeMatrices()
    {
        return array(
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(2,4,6), array(6,4,2), array(4,2,6)),
                'IntType'
            ),
              array(
                array(array(-1.12,2.12,3.12), array(3.12,2.12,1.12), array(2.12,1.12,3.12)),
                array(array(-1.12,2.12,3.12), array(3.12,2.12,1.12), array(2.12,1.12,3.12)),
                array(array(-2.24,4.24,6.24), array(6.24,4.24,2.24), array(4.24,2.24,6.24)),
                'Rational\RationalType'
            ),
            array(
                array(array(true,false,true,false)),
                array(array(true,false,false,true)),
                array(array(2,0,1,1)),
                'IntType'
            ),
        );
    }

    private function toStrongType(array $values, $expectedType)
    {
        $ns = '\chippyash\Type\Number\\';
        $class = $ns . $expectedType;
        $ret = [];
        foreach ($values as $r => $row) {
            foreach ($row as $c => $item) {
                if ($expectedType == 'Rational\RationalType') {
                    $ret[$r][$c] = RationalTypeFactory::fromFloat($item);
                } else {
                    $ret[$r][$c] = new $class($item);
                }
            }
        }
        return $ret;
    }
}

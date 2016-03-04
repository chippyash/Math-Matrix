<?php
namespace Chippyash\Test\Math\Matrix\Computation\Add;
use Chippyash\Math\Matrix\Computation\Add\Scalar;
use Chippyash\Math\Matrix\NumericMatrix;

/**
 * Description of ScalarTest
 *
 * @author akitson
 */
class ScalarTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Scalar();
    }

    public function testComputeAcceptsScalarValue()
    {
        $m = new NumericMatrix(array());
        $this->object->compute($m, 0);
        $this->object->compute($m, 1.23);
        $this->object->compute($m, 'foo');
        $this->object->compute($m, true);
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new NumericMatrix(array());
        $test = $this->object->compute($m, 1);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($operand, $test, $scalar)
    {
        $m = new NumericMatrix($operand);
        $testM = new NumericMatrix($test);
        $this->assertEquals($testM, $this->object->compute($m, $scalar));
    }

    public function computeMatrices()
    {
        return [
            [
                [[1,2,3], [3,2,1], [2,1,3]],
                [[3,4,5], [5,4,3], [4,3,5]],
                2
            ],
              [
                [[1,2,3], [3,2,1], [2,1,3]],
                [[3.12,4.12,5.12], [5.12,4.12,3.12], [4.12,3.12,5.12]],
                2.12
            ],
              [
                [[1.12,2.12,3.12], [3.12,2.12,1.12], [2.12,1.12,3.12]],
                [[3.12,4.12,5.12], [5.12,4.12,3.12], [4.12,3.12,5.12]],
                2
            ],
              [
                [[1.12,2.12,3.12], [3.12,2.12,1.12], [2.12,1.12,3.12]],
                [[3.24,4.24,5.24], [5.24,4.24,3.24], [4.24,3.24,5.24]],
                2.12
            ],
            [
                [[1,2,3], [3,2,1], [2,1,3]],
                [[2,3,4], [4,3,2], [3,2,4]],
                true
            ],
            [
                [[1,2,3], [3,2,1], [2,1,3]],
                [[1,2,3], [3,2,1], [2,1,3]],
                false
            ],
            [
                [[true,false]],
                [[2, 1]],
                true
            ],
            [
                [[true,false]],
                [[1, 0]],
                false
            ],
        ];
    }
}

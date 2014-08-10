<?php
namespace chippyash\Test\Math\Matrix\Derivative\Strategy\Determinant;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Math\Matrix\Derivative\Strategy\Determinant\Laplace;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Type\Number\Complex\ComplexTypeFactory;
use chippyash\Type\String\StringType;

/**
 */
class LaplaceTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Laplace();
    }

    public function testEmptyMatrixReturnsOne()
    {
        $this->assertEquals(1, $this->object->determinant(new NumericMatrix([]))->get());
        $this->assertEquals(1, $this->object->determinant(new RationalMatrix([]))->get());
        $this->assertEquals(1, $this->object->determinant(new ComplexMatrix([]))->get());
    }

    public function testSingleMatrixReturnsValueOfItsSingleEntry()
    {
        $this->assertEquals(2, $this->object->determinant(new NumericMatrix([2]))->get());
        $this->assertEquals('2/5', (string) $this->object->determinant(new RationalMatrix([RationalTypeFactory::fromString('2/5')])));
        $this->assertEquals('1+3i', $this->object->determinant(new ComplexMatrix([ComplexTypeFactory::fromString('1+3i')]))->get());
    }

    /**
     * @dataProvider singularTwosMatrices
     */
    public function testSingularTwoByTwoMatricesReturnZero($arr)
    {
        $this->assertEquals(0, $this->object->determinant(new NumericMatrix($arr))->get());
        $this->assertEquals(0, $this->object->determinant(new RationalMatrix($arr))->get());
        $this->assertEquals(0, $this->object->determinant(new ComplexMatrix($arr))->get());
    }

    /**
     * @dataProvider NonSingularTwosMatrices
     */
    public function testNonSingularTwoByTwoMatricesReturnNonZero($arr, $result)
    {
        $this->assertEquals($result, $this->object->determinant(new NumericMatrix($arr))->get());
        $this->assertEquals($result, $this->object->determinant(new RationalMatrix($arr))->get());
        $this->assertEquals($result, $this->object->determinant(new ComplexMatrix($arr))->get());
    }

    /**
     * @dataProvider singularThreesMatrices
     */
    public function testSingularThreeByThreeMatricesReturnZero($arr)
    {
        $this->assertEquals(0, $this->object->determinant(new NumericMatrix($arr))->get());
        $this->assertEquals(0, $this->object->determinant(new RationalMatrix($arr))->get());
        $this->assertEquals(0, $this->object->determinant(new ComplexMatrix($arr))->get());
    }

    /**
     * @dataProvider singularFoursMatrices
     */
    public function testSingularFourByFourMatricesReturnZero($arr)
    {
        $this->assertEquals(0, $this->object->determinant(new NumericMatrix($arr))->get());
        $this->assertEquals(0, $this->object->determinant(new RationalMatrix($arr))->get());
        $this->assertEquals(0, $this->object->determinant(new ComplexMatrix($arr))->get());
    }


    /**
     * @dataProvider nonSingularThreesMatrices
     */
    public function testNonSingularThreeByThreeMatricesReturnNonZero($arr, $result)
    {
        $this->assertEquals($result, $this->object->determinant(new NumericMatrix($arr))->get());
        $this->assertEquals($result, $this->object->determinant(new RationalMatrix($arr))->get());
        $this->assertEquals($result, $this->object->determinant(new ComplexMatrix($arr))->get());
    }

    /**
     * @dataProvider nonSingularNxNMatrices
     */
    public function testNonSingularNByNMatricesReturnNonZero($arr, $result)
    {
        $this->assertEquals($result, $this->object->determinant(new NumericMatrix($arr))->get());
        $this->assertEquals($result, $this->object->determinant(new RationalMatrix($arr))->get());
        $this->assertEquals($result, $this->object->determinant(new ComplexMatrix($arr))->get());
    }

    public function testTuneClearCacheFalseDoesNotClearCache()
    {
        $this->object->tune(new StringType('clearCache'), true);
        $this->object->determinant(new NumericMatrix([[1,2],[3,4]]));
        $c1 = $this->object->tune(new StringType('clearCache'), false);
        $this->object->determinant(new NumericMatrix([[-12,2],[3,4]]));
        $c2 = $this->object->tune(new StringType('clearCache'), false);
        $this->assertGreaterThan($c1, $c2);
    }

    public function testTuneClearCacheTrueDoesClearCache()
    {
        $this->object->tune(new StringType('clearCache'), true);
        $this->object->determinant(new NumericMatrix([[1,2],[3,4]]));
        $c1 = $this->object->tune(new StringType('clearCache'), true);
        $this->object->determinant(new NumericMatrix([[-12,2,13],[3,4,3],[7,8,-4]]));
        $c2 = $this->object->tune(new StringType('clearCache'), true);
        $this->assertGreaterThan($c1, $c2);
        $c3 = $this->object->tune(new StringType('clearCache'), false);
        $this->assertEquals(0, $c3);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage foo is unknown for tuning
     */
    public function testTuningWithInvalidNameThrowsException()
    {
        $this->object->tune(new StringType('foo'), 'bar');
    }

    /**
     *
     * @return array [[matrix],..]
     */
    public function singularTwosMatrices()
    {
        //all known singular 2x2 matrices
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
        ];
    }

    /**
     *
     * @return array [[matrix],..]
     */
    public function nonSingularTwosMatrices()
    {
        return [
            [[[1,2],[3,4]],-2]
        ];
    }

    /**
     *
     * @return array [[matrix],..]
     */
    public function singularThreesMatrices()
    {
        return [
            [
                [[1,2,3],
                 [4,5,6],
                 [7,8,9]]]
        ];
    }

    public function singularFoursMatrices()
    {
        return [
            //the Durer magic 4x4 matrix.:
            [[[32, 8, 11, 17],[8, 20, 17, 23],[11, 17, 14, 26],[17, 23, 26, 2]],0]
        ];
    }

    /**
     *
     * @return array[[matrix, result],...]
     */
    public function nonSingularThreesMatrices()
    {
        return [
            [[[1,2,3],[4,5,6],[7,8,0]],27]
        ];
    }

    public function nonSingularNxNMatrices()
    {
        return [
            //unimodular matrices - det = abs(1)
            [[[2,3,1,5],[1,0,3,1],[0,2,-3,2 ],[0,2,3,1]],1],
            [[[1,1,1,1],[1,2,1,2],[1,1,1,0],[1,4,2,3]],1],
            //other matrices
            [[[33, 9, 12, 18],[58, 20, 17, 23],[11, -34, 14, 26],[17, 23, -26, 2]],-221490]
        ];
    }
}

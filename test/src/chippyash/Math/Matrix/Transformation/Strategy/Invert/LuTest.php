<?php
namespace chippyash\Test\Math\Matrix\Transformation\Strategy\Invert;
use chippyash\Math\Matrix\Transformation\Strategy\Invert\LU;
use chippyash\Math\Matrix\Matrix;

/**
 */
class LuTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new LU();
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Can only perform inversion on non singular matrix
     * @link http://mathworld.wolfram.com/SingularMatrix.html
     */
    public function testComputeThrowsExceptionIfMatrixIsNonSingular()
    {
        $m = new Matrix(array(array(0,0),array(0,0)));
        $this->object->invert($m);
    }

}

<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Transformation;

use chippyash\Matrix\Transformation\AbstractTransformation;
use chippyash\Matrix\Exceptions\NoInverseException;
use chippyash\Matrix\Exceptions\UndefinedComputationException;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;
use chippyash\Matrix\Traits\AssertMatrixIsNonSingular;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Matrix\Transformation\Strategy\Invert\Determinant;
use chippyash\Matrix\Transformation\Strategy\Invert\GaussJordan;
use chippyash\Math\Matrix\RationalMatrix;

//use chippyash\Matrix\Transformation\Strategy\Invert\LU;
use chippyash\Matrix\Traits\Debug;

/**
 * Invert a matrix i.e. produce its inverse matrix
 * Square, non singular matrices only!
 *
 * @link http://www.intmath.com/matrices-determinants/inverse-matrix-gauss-jordan-elimination.php
 * @link https://gist.github.com/unix1/7510208
 * @todo reinstate lu method once fixed
 */
class Invert extends AbstractTransformation
{
    use AssertMatrixIsSquare;
    use AssertMatrixIsNonSingular;
    use AssertMatrixIsRational;
    use Debug;

//    const METHOD_LU = 0;  //LU Decomposition method
    const METHOD_GJ = 1;  //Gauss-Jordan method
    const METHOD_DET = 2; //By determinant method

    /**
     * Which inversion method to use
     * @var int
     */
    protected $method;

    /**
     * Constructor
     * @param int $method
     */
    public function __construct($method = self::METHOD_GJ)
    {
        $this->method = $method;
    }

    /**
     * Invert the matrix
     *
     * $mA must be square and nonsingular.
     * An empty $mA returns an empty matrix
     * A single entry matrix e.g. [[2]] returns [[1/n]]
     * A zero single item matrix e.g. [[0]] throws an exception (Division by zero)
     *
     * @param Matrix $mA First matrix operand - required
     * @param mixed $extra ignored
     *
     * @return Matrix
     *
     * @throws chippyash\Matrix\Exceptions\ComputationException
     * @throws chippyash\Matrix\Exceptions\UndefinedComputationException
     */
    public function transform(Matrix $mA, $extra = null)
    {
        $this->debug('Start', $mA);

        if ($mA->is('empty')) {
            return new Matrix([]);
        }
        if ($mA->is('singleitem')) {
            $i = $mA->get(1,1);
            if ($i == 0) {
                throw new ComputationException('Division by zero');
            } else {
                return new Matrix([1/$i]);
            }
        }
        $this->assertMatrixIsSquare($mA)
             ->assertMatrixIsRational($mA, 'Matrix mA is not rational')
             ->assertMatrixIsNonSingular($mA,'Can only perform inversion on non singular matrix');

        $I = $this->invert($mA);

        $this->debug("Finish", $I);

        return $I;
    }

    /**
     * Do the inversion
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return \chippyash\Matrix\Matrix
     * @throws UndefinedComputationException
     */
    protected function invert(Matrix $mA)
    {
        switch ($this->method) {
//            case self::METHOD_LU:
//                $strategy = new LU();
//                break;
            case self::METHOD_GJ:
                $strategy = new GaussJordan();
                break;
            case self::METHOD_DET:
                $strategy = new Determinant();
                break;
            default:
                throw new UndefinedComputationException('Unknown Inverse computation method');
        }

        return $strategy->invert($mA);
    }
}

<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Transformation;

use chippyash\Matrix\Transformation\AbstractTransformation;
use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\Exceptions\UndefinedComputationException;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNonSingular;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use chippyash\Math\Matrix\Transformation\Strategy\Invert\Determinant;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Type\Calculator;

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
    use AssertMatrixIsNumeric;
    use AssertMatrixIsNonSingular;
    use CreateCorrectMatrixType;

    const METHOD_AUTO = 0; //Auto determine
    const METHOD_DET = 1; //By Determinant method

    /**
     * Which inversion method to use
     * @var int
     */
    protected $method;

    /**
     * Inversion methods supported by this transformation
     *
     * @var array
     */
    private $supportedMethods = [self::METHOD_DET, self::METHOD_AUTO];

    /**
     * Constructor
     * @param int $method
     */
    public function __construct($method = self::METHOD_AUTO)
    {
        if (in_array($method, $this->supportedMethods)) {
            $this->method = $method;
        } else {
            throw new UndefinedComputationException('Unknown Inverse computation method');
        }
    }

    /**
     * Invert the matrix
     *
     * $mA must be square and nonsingular.
     * An empty $mA returns an empty matrix
     * A single entry matrix e.g. [[n]] returns [[1/n]]
     * A zero single item matrix e.g. [[0]] throws an exception (Division by zero)
     *
     * @param chippyash\Math\Matrix\NumericMatrix $mA First matrix operand - required
     * @param mixed $extra ignored
     *
     * @return chippyash\Math\Matrix\NumericMatrix
     *
     * @throws chippyash\Matrix\Exceptions\ComputationException
     * @throws chippyash\Matrix\Exceptions\UndefinedComputationException
     */
    protected function doTransform(Matrix $mA, $extra = null)
    {
        $this->assertMatrixIsNumeric($mA);

        if ($mA->is('empty')) {
            return $this->createCorrectMatrixType($mA);
        }
        if ($mA->is('singleitem')) {
            $item = $mA->get(1,1)->get();
            if ($item == 0 || $item == '0+0i') {
                throw new ComputationException('Division by zero');
            } else {
                $calc = new Calculator();
                return $this->createCorrectMatrixType($mA, [$calc->reciprocal($mA->get(1,1))]);
            }
        }
        $this->assertMatrixIsNonSingular($mA,'Can only perform inversion on non singular matrix');

        $I = $this->invert($mA);

        return $I;
    }

    /**
     * Do the inversion
     *
     * At the current time, only the determinant method is supported.
     * In the future, alternative methods will be supported that can be
     * used dependent on the type of matrix, user preference etc.
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $mA
     * @return \chippyash\Math\Matrix\NumericMatrix
     */
    protected function invert(NumericMatrix $mA)
    {
        switch ($this->method) {
            case self::METHOD_AUTO:
            case self::METHOD_DET:
                $strategy = new Determinant();
                break;
        }

        return $strategy->invert($mA);
    }
}

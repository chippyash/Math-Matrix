<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Derivative;

use chippyash\Math\Matrix\Derivative\AbstractDerivative;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Exceptions\UndefinedComputationException;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;
use chippyash\Math\Matrix\Derivative\Strategy\Determinant\Laplace;
use chippyash\Math\Matrix\Derivative\Strategy\Determinant\Lu;
use chippyash\Math\Matrix\Interfaces\TuningInterface;
use chippyash\Type\String\StringType;

/**
 * Find the Determinant of a square matrix det(M)
 */
class Determinant extends AbstractDerivative implements TuningInterface
{
    use AssertMatrixIsSquare;

    const METHOD_AUTO = 0;
    const METHOD_LAPLACE = 1;
    const METHOD_LU = 2;

    /**
     * Maximum matrix size that will be handled by the LU decomposition method
     * when in auto mode
     * @var int
     */
    static protected $luLimit = 20;

    /**
     * Which derivative method to use
     * @var int
     */
    protected $method;

    /**
     * Constructor
     * @param int $method
     */
    public function __construct($method = self::METHOD_AUTO)
    {
        $this->method = $method;
    }

    /**
     * Find det(M)
     * $mA must be none empty AND square
     *
     * @param RationalMatrix $mA
     * @param mixed $extra
     * @return numeric
     *
     * @throws chippyash/Math/Matrix/Exceptions/UndefinedComputationException
     * @throws chippyash/Math/Matrix/Exceptions/ComputationException
     */
    public function derive(NumericMatrix $mA, $extra = null)
    {
        $this->assertMatrixIsSquare($mA, 'No determinant for non-square matrix');

        return $this->getDeterminant($mA);
    }

    /**
     * Tune an item on a class. Available items:
     * - luLimit: int Matrix size (n=m) limit for LU decomposition when
     *   using the auto method
     *
     * @param \chippyash\Type\String\StringType $name Item to tune
     * @param mixed $value Value to set
     *
     * @return mixed - previous value of item
     *
     * @throws \InvalidArgumentException if name does not exist
     */
    public function tune(StringType $name, $value)
    {
        if ($name() != 'luLimit') {
            throw new \InvalidArgumentException("{$name} is unknown for tuning");
        }

        $ret = self::$luLimit;
        self::$luLimit = $value;

        return $ret;
    }

    /**
     * Compute determinant using a strategy
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $mA
     * @return numeric
     * @throws UndefinedComputationException
     *
     * @todo put back in LU determinant strategy once figured out what is wrong with it
     */
    protected function getDeterminant(NumericMatrix $mA)
    {
        switch ($this->method) {
            case self::METHOD_AUTO;
                if ($mA->rows() <= self::$luLimit) {
                    $strategy = new Lu();
                } else {
                    throw new UndefinedComputationException('No available strategy found to determine the determinant');
                }
                break;
            case self::METHOD_LAPLACE:
                $strategy = new Laplace();
                break;
            case self::METHOD_LU;
                $strategy = new Lu();
                break;
            default:
                throw new UndefinedComputationException('Unknown determinant computation method');
        }

        return $strategy->determinant($mA);
    }
}

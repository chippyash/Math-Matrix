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
use chippyash\Matrix\Traits\AssertMatrixIsNotEmpty;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;
use chippyash\Math\Matrix\Derivative\Strategy\Determinant\Internal;
use chippyash\Math\Matrix\Derivative\Strategy\Determinant\Lu;

/**
 * Find the Determinant of a square matrix det(M)
 */
class Determinant extends AbstractDerivative
{
    use AssertMatrixIsNotEmpty;
    use AssertMatrixIsSquare;

    const METHOD_INTERNAL = 0;
//    const METHOD_LU = 1;


    /**
     * Which derivative method to use
     * @var int
     */
    protected $method;

    /**
     * Constructor
     * @param int $method
     */
    public function __construct($method = self::METHOD_INTERNAL)
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
        $this->assertMatrixIsNotEmpty($mA, 'No determinant for empty matrix')
             ->assertMatrixIsSquare($mA, 'No determinant for non-square matrix');

        return $this->getDeterminant($mA);
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
            case self::METHOD_INTERNAL:
                $strategy = new Internal();
                break;
//            case self::METHOD_LU:
//                $strategy = new Lu();
//                break;
            default:
                throw new UndefinedComputationException('Unknown determinant computation method');
        }

        return $strategy->determinant($mA);
    }
}

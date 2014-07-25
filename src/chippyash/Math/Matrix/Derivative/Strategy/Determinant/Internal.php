<?php

/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Derivative\Strategy\Determinant;

use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\RationalNumber;
use chippyash\Math\Matrix\Interfaces\DeterminantStrategyInterface;
use chippyash\Matrix\Transformation\Cofactor;

/**
 * Determinant strategy for matrix inversion
 *
 */
class Internal implements DeterminantStrategyInterface
{

    /**
     * Cofactor function
     * @var function
     */
    protected $fCof;

    /**
     * Compute determinant using internal method
     * $mA must be
     * - square
     * This is not checked here - that is done in the determinant derivative class
     *
     * @param RationalMatrix $mA
     * @return RationalNumber
     */
    public function determinant(RationalMatrix $mA)
    {
        $rowCount = $mA->rows();
        if ($rowCount == 2) { //2X2 matrix
            return $this->det2($mA);
        }

        $this->fCof = new Cofactor();
        if ($rowCount == 3) { //3X3 matrix
            return $this->det3($mA);
        }

        if ($rowCount > 3) { //nXn matrix
            return $this->detN($mA);
        }
    }

    /**
     * Return determinant of a 2X2 matrix
     * @link http://en.wikipedia.org/wiki/Matrix_determinant#2.C2.A0.C3.97.C2.A02_matrices
     * [[a, b]
     *  [c, d]]
     * ad - bc
     *
     * @param RationalMatrix $mA
     * @return RationalNumber
     */
    protected function det2(RationalMatrix $mA)
    {
        $data = $mA->toArray();
        
        return (
                $data[0][0]->multiplyBy($data[1][1])
                ->subtract($data[0][1]->multiplyBy($data[1][0]))
        );
    }

    /**
     * Get determinant for 3 X 3 matrix
     *
     * @param RationalMatrix $mA
     * @return RationalNumber
     */
    protected function det3(RationalMatrix $mA)
    {
        $det = "0";
        $positive = true;
        for ($r = 1; $r < 4; $r++) {
            $t = bcmul((string) $mA->get($r, 1), $this->det2($this->fCof->transform($mA,
                                    array($r, 1))));
            $det = bcadd($det, (string) ($positive ? $t : -$t));
            $positive = !$positive;
        }

        return $det;
    }

    /**
     * Get determinant for arbitrarily large matrices
     *
     * @link http://www.intmath.com/matrices-determinants/2-large-determinants.php
     * @param RationalMatrix $mA
     * @return RationalNumber
     */
    protected function detN(RationalMatrix $mA)
    {
        $det = "0";
        $positive = true;
        $rowLimit = $mA->rows() + 1;
        for ($r = 1; $r < $rowLimit; $r ++) {
            $t = bcmul((string) $mA->get($r, 1), $this->determinant($this->fCof->transform($mA,
                                    array($r, 1))));
            $det = bcadd($det, (string) ($positive ? $t : -$t));
            $positive = !$positive;
        }
        return $det;
    }

}

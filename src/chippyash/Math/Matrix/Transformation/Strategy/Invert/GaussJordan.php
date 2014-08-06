<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Transformation\Strategy\Invert;

use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\IdentityMatrix;
use chippyash\Math\Matrix\Interfaces\InversionStrategyInterface;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Type\Calculator;
use chippyash\Math\Type\Comparator;
use chippyash\Type\Number\IntType;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;

/**
 * Gauss-Jordan strategy for matrix inversion
 *
 */
class GaussJordan implements InversionStrategyInterface
{
    use CreateCorrectMatrixType;

    /**
     * Compute inverse using Gauss-Jordan method
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return Matrix
     */
    public function invert(NumericMatrix $mA)
    {
        //setup
        $size = $mA->rows();
        if ($mA instanceof ComplexMatrix) {
            $mI = IdentityMatrix::complexIdentity(new IntType($size));
        } elseif ($mA instanceof RationalMatrix) {
            $mI = IdentityMatrix::rationalIdentity(new IntType($size));
        } else {
            $mI = IdentityMatrix::numericIdentity(new IntType($size));
        }
        $work = $mA("Concatenate", $mI)->toArray();
        $calc = new Calculator();
        $comp = new Comparator();

        //do the work
        // forward run
        for ($j = 0; $j < $size - 1; ++$j) {
            // for all remaining rows (diagonally)
            for ($i = $j + 1; $i < $size; ++$i) {
                // adjust scale to pivot row
                // subtract pivot row from current
                $scalar = $calc->div($work[$j][$j], $work[$i][$j]);
                for ($jj = $j; $jj < $size * 2; ++$jj) {
                    $scalar = $calc->mul($work[$i][$jj], $scalar);
                    $work[$i][$jj] = $calc->sub($work[$i][$jj], $work[$j][$jj]);
                }
            }
        }

        // reverse run
        for ($j = $size - 1; $j > 0; --$j) {
            for ($i = $j - 1; $i >= 0; --$i) {
                $scalar = $calc->div($work[$j][$j], $work[$i][$j]);
                for ($jj = $i; $jj < $size * 2; ++$jj) {
                    $work[$i][$jj] = $calc->mul($work[$i][$jj], $scalar);
                    $work[$i][$jj] = $calc->sub($work[$i][$jj], $work[$j][$jj]);
                }
            }
        }

        // last run to make all diagonal 1s
        // @note this can be done in last iteration (i.e. reverse run) too!
        $one = new IntType(1);
        for ($j = 0; $j < $size; ++$j) {
            if ($comp->neq($work[$j][$j], $one)) {
                $scalar = $calc->div($one, $work[$j][$j]);
                for ($jj = $j; $jj < $size * 2; ++$jj) {
                    $work[$j][$jj] = $calc->mul($work[$j][$jj], $scalar);
                }
            }
        }

        $mC = $this->createCorrectMatrixType($mA, $work);

        //extract result
        return $mC('Colslice', array($size + 1, $size));
    }
}

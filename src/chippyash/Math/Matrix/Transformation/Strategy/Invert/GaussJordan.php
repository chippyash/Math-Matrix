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

use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Matrix\IdentityMatrix;
use chippyash\Matrix\Interfaces\InversionStrategyInterface;

/**
 * Gauss-Jordan strategy for matrix inversion
 *
 */
class GaussJordan implements InversionStrategyInterface
{
    /**
     * Compute inverse using Gauss-Jordan method
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return Matrix
     */
    public function invert(Matrix $mA)
    {
        //setup
        $size = $mA->rows();
        $work = $mA("Concatenate", new IdentityMatrix($size))->toArray();

        //do the work
        // forward run
        for ($j = 0; $j < $size - 1; ++$j) {
            // for all remaining rows (diagonally)
            for ($i = $j + 1; $i < $size; ++$i) {
                // adjust scale to pivot row
                // subtract pivot row from current
                $scalar = $work[$j][$j] / $work[$i][$j];
                for ($jj = $j; $jj < $size * 2; ++$jj) {
                    $work[$i][$jj] *= $scalar;
                    $work[$i][$jj] -= $work[$j][$jj];
                }
            }
        }

        // reverse run
        for ($j = $size - 1; $j > 0; --$j) {
            for ($i = $j - 1; $i >= 0; --$i) {
                $scalar = $work[$j][$j] / $work[$i][$j];
                for ($jj = $i; $jj < $size * 2; ++$jj) {
                    $work[$i][$jj] *= $scalar;
                    $work[$i][$jj] -= $work[$j][$jj];
                }
            }
        }

        // last run to make all diagonal 1s
        // @note this can be done in last iteration (i.e. reverse run) too!
        for ($j = 0; $j < $size; ++$j) {
            if ($work[$j][$j] !== 1) {
                $scalar = 1 / $work[$j][$j];
                for ($jj = $j; $jj < $size * 2; ++$jj) {
                    $work[$j][$jj] *= $scalar;
                }
            }
        }

        $mC = new Matrix($work);

        //extract result
        return $mC('Colslice', array($size + 1, $size));
    }
}

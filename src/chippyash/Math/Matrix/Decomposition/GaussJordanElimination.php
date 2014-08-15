<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @copyright Jan-Erik Revsbech <janerik@moc.net>
 * @copyright Andres Angulo <andres@moc.net>
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Decomposition;

use chippyash\Math\Matrix\Decomposition\AbstractDecomposition;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use chippyash\Math\Matrix\Exceptions\SingularMatrixException;
use chippyash\Matrix\Traits\AssertParameterIsMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;
use chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;
use chippyash\Type\Number\IntType;
use chippyash\Math\Type\Calculator;
use chippyash\Math\Type\Comparator;

/**
 * Gauss Jordan Elimination
 *
 * This is largely copied from the moc/math library with variation to fit
 * this library and it's use of strong numeric types.
 *
 * Unlike the original, this does not alter the original matrices and returns
 * (a clone of) itself to fit with the Decomposition interface.
 *
 * The inline comments are largely the same, so hopefully the original authors
 * will recognise it :-)
 */
class GaussJordanElimination extends AbstractDecomposition
{

    use AssertParameterIsMatrix;
    use AssertMatrixIsNumeric;
    use AssertMatrixIsSquare;
    use AssertMatrixRowsAreEqual;
    use CreateCorrectMatrixType;

    /**
     * Products of the decomposition
     * - left : NumericMatrix - product of left side
     * - right : NumericMatrix - product of right side
     *
     * @var array [productName => mixed,...]
     */
    protected $products = array(
        'left' => null,
        'right' => null
    );

    /**
     * Perform Guass Jordan Elimination on the two supplied matrices
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param NumericMatrix $extra Second matrix to act upon - required
     *
     * @return chippyash\Matrix\Decomposition\AbstractDecomposition Fluent Interface
     *
     * @throws chippyash\Math\Matrix\Exceptions\SingularMatrixException
     */
    public function decompose(NumericMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter extra is not a matrix')
                ->assertMatrixIsNumeric($extra, 'Parameter extra is not a numeric matrix')
                ->assertMatrixIsSquare($mA,
                        'Parameter mA is not a square matrix')
                ->assertMatrixRowsAreEqual($mA, $extra, 'mA->rows != extra->rows');

        $rows = $mA->rows();
        $dA = $mA->toArray();
        $dB = $extra->toArray();
        $zero = function(){return new IntType(0);};
        $one = function(){return new IntType(1);};
        $calc = new Calculator();
        $comp = new Comparator();

        $ipiv = array_fill(0, $rows, $zero());
        $indxr = array_fill(0, $rows, 0);
        $indxc = array_fill(0, $rows, 0);

        // find the pivot element by searching the entire matrix for its largest value, but excluding columns already reduced.
        $irow = $icol = 0;
        for ($i = 0; $i < $rows; $i++) {
            $big = $zero();
            for ($j = 0; $j < $rows; $j++) {
                if ($comp->neq($ipiv[$j], $one())) {
                    for ($k = 0; $k < $rows; $k++) {
                        if ($comp->eq($ipiv[$k], $zero())) {
                            $absVal = $dA[$j][$k]->abs();
                            if ($comp->gt($absVal, $big)) {
                                unset($big);
                                $big = clone $absVal;
                                $irow = $j;
                                $icol = $k;
                            }
                        } elseif ($comp->gt($ipiv[$k], $one())) {
                            throw new SingularMatrixException('GaussJordanElimination');
                        }
                    }
                }
            }

            //Now interchange row to move the pivot element to a diagonal
            $ipiv[$icol] = $calc->add($ipiv[$icol], $one());
            if ($irow != $icol) {
                $this->swapRows($dA, $irow, $icol);
                $this->swapRows($dB, $irow, $icol);
            }

            // Remember permutations to later
            $indxr[$i] = $irow;
            $indxc[$i] = $icol;
            if ($comp->eq($dA[$icol][$icol], $zero())) {
                throw new SingularMatrixException('GaussJordanElimination');
            }

            // Now divide the found row to make the pivot element 1
            // To maintain arithmetic integrity we use the reciprocal to multiply by
            $pivinv = $calc->reciprocal($dA[$icol][$icol]);
            $this->multRow($dA, $icol, $pivinv, $calc);
            $this->multRow($dB, $icol, $pivinv, $calc);

            // And reduce all other rows but the pivoted row with the value of the pivot row
            for ($ll = 0; $ll < $rows; $ll++) {
                if ($ll != $icol) {
                    $multiplier = clone $dA[$ll][$icol];
                    $multiplier->negate();
                    $this->addMultipleOfOtherRowToRow($dA, $multiplier, $icol, $ll, $calc);
                    $this->addMultipleOfOtherRowToRow($dB, $multiplier, $icol, $ll, $calc);
                }
            }

        }

        $this->set('left', $this->createCorrectMatrixType($mA, $dA));
        $this->set('right', $this->createCorrectMatrixType($extra, $dB));

        return clone $this;
    }

    /**
     * Swap rows in an array
     *
     * @param array $a
     * @param int $r1
     * @param int $r2
     */
    protected function swapRows(array &$a, $r1, $r2)
    {
        $tmp = $a[$r1];
        $a[$r1] = $a[$r2];
        $a[$r2] = $tmp;
    }

    /**
     * Multiply each entry in a row by a number
     *
     * @param array $a
     * @param int $row
     * @param NumericTypeInterface $num
     * @param \chippyash\Math\Type\Calculator $calc
     */
    protected function multRow(array &$a, $row, $num, Calculator $calc)
    {
        foreach ($a[$row] as &$value) {
            $value = $calc->mul($value, $num);
        }
    }

    /**
     * Inter row multiplication
     *
     * @param array $a
     * @param NumericTypeInterface $multiple
     * @param int $rowToMultiplyWith
     * @param int $rowToAddTo
     * @param \chippyash\Math\Type\Calculator $calc
     */
    protected function addMultipleOfOtherRowToRow(array &$a, $multiple, $rowToMultiplyWith,
            $rowToAddTo, Calculator $calc)
    {
        $numberOfColumns = count($a[0]);
        for ($l = 0; $l < $numberOfColumns; $l++) {
            $a[$rowToAddTo][$l] = $calc->add(
                    $a[$rowToAddTo][$l],
                    $calc->mul(
                            $a[$rowToMultiplyWith][$l],
                            $multiple));
        }
    }

}

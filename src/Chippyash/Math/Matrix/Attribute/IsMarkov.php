<?php
/**
 * Markov
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Attribute;


use Chippyash\Math\Matrix\Derivative\Sum;
use Chippyash\Math\Type\Comparator;
use Chippyash\Matrix\Exceptions\MatrixException;
use Chippyash\Matrix\Interfaces\AttributeInterface;
use Chippyash\Matrix\Matrix;
use Chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use Chippyash\Matrix\Traits\AssertMatrixIsSquare;

/**
 * IsMarkov Matrix Attribute
 * Is it A NumericMatrix?
 * Is it Square?
 * Are all row sums equal i.e. is the probability graph complete?
 * Are there at least 2 rows and columns?
 */
class IsMarkov implements AttributeInterface
{
    use AssertMatrixIsNumeric;
    use AssertMatrixIsSquare;

    /**
     * Does the matrix exhibit this attribute
     *
     * @param Matrix $mA
     *
     * @return boolean
     */
    public function is(Matrix $mA)
    {
        try {
            $this->assertMatrixIsNumeric($mA)
                 ->assertMatrixIsSquare($mA);
        } catch (MatrixException $e) {
            return false;
        }
        //we've already assured it is square, now make sure we at least have a
        // cyclic probability
        if ($mA->rows() < 2 || $mA->columns() < 2) {
            return false;
        }

        $sumDerivative = new Sum();
        $firstRow = $mA('Rowslice', [1])->derive($sumDerivative);
        $comp = new Comparator();
        //check that each row has same sum
        foreach(range(2, $mA->rows()) as $row) {
            if ($comp->neq($firstRow, $mA('RowSlice', [$row])->derive($sumDerivative)) != 0) {
                return false;
            }
        }

        return true;
    }
}
<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Decomposition;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Type\Calculator;
use Chippyash\Math\Type\Comparator;
use Chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use Chippyash\Math\Matrix\Traits\CreateCorrectScalarType;

/**
 * This is lifted from the JAMA package and adapted for this library from its
 * PHP4 origin into PHP 5 and to fit the Chippyash\Matrix model
 * You can find the JAMA package at http://www.phpmath.com/build02/JAMA/downloads/
 * My thanks to the original authors mentioned below
 *
 * For an m-by-n matrix A with m >= n, the LU decomposition is an m-by-n
 * unit lower triangular matrix L, an n-by-n upper triangular matrix U,
 * and a permutation vector piv of length m so that A(piv,:) = L*U.
 * If m < n, then L is m-by-m and U is m-by-n.
 *
 * The LU decompostion with pivoting always exists, even if the matrix is
 * singular, so the constructor will never fail.  The primary use of the
 * LU decomposition is in the solution of square systems of simultaneous
 * linear equations.  This will fail if isNonsingular() returns false.
 *
 * @author Paul Meagher
 * @author Bartosz Matosiuk
 * @author Michael Bommarito
 * @version 1.1
 * @license PHP v3.0
 */
class Lu extends AbstractDecomposition
{
    use CreateCorrectMatrixType;
    use CreateCorrectScalarType;

    /**
     * Products of the decomposition
     * @var array [productName => Matrix,...]
     */
    protected $products = array(
        'LU' => null, //NumericMatrix: full decomposition
        'L' => null,  //NumericMatrix: lower triangle
        'U' => null,   //NumericMatrix: upper triangle
        'PivotVector' => null, //NumericMatrix: Pivot vector of the decomposition,
        'PermutationMatrix' => null, //NumericMatrix: permutation matrix of decomposition,
        'Det' => null //NumericTypeInterface|null: determinant of source matrix if matrix is square else null
    );

    /**
     * Internal storage of pivot vector.
     * @var array
     */
    protected $piv = [];

    /**
     * Pivot sign - used for Det
     * @var int
     */
    protected $pivsign;

    /**
     * Number of rows in original matrix
     * @var int
     */
    protected $rows;
    /**
     * Number of columns in original matrix
     * @var int
     */
    protected $cols;

    /**
     * Do the decomposition
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param mixed $extra ignored
     * @return \Chippyash\Matrix\Transformation\Decomposition\Lu
     */
    public function decompose(NumericMatrix $mA, $extra = null)
    {
        $this->LUDecomposition($mA);

        $this->setOtherProducts($mA);

        return clone $this;
    }

    /**
     * LU Decomposition constructor.
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     */
    protected function LUDecomposition(NumericMatrix $mA)
    {
        // Use a "left-looking", dot-product, Crout/Doolittle algorithm.
        $LU = $mA->toArray();
        $m = $this->rows = $mA->rows();
        $n = $this->cols = $mA->columns();
        for ($i = 0; $i < $m; $i++) {
            $this->piv[$i] = $i;
        }
        $this->pivsign = 1;
        $LUrowi = [];
        $LUcolj = [];
        $calc = new Calculator();
        $comp = new Comparator();
        $zeroInt = $this->createCorrectScalarType($mA, 0);

        // Outer loop.
        for ($j = 0; $j < $n; $j++) {
            // Make a copy of the j-th column to localize references.
            for ($i = 0; $i < $m; $i++) {
                $LUcolj[$i] = &$LU[$i][$j];
            }
            // Apply previous transformations.
            for ($i = 0; $i < $m; $i++) {
                $LUrowi = $LU[$i];
                // Most of the time is spent in the following dot product.
                $kmax = min($i, $j);
                $s = clone $zeroInt;
                for ($k = 0; $k < $kmax; $k++) {
                    $s = $calc->add($s, $calc->mul($LUrowi[$k], $LUcolj[$k]));
                }
                $LUcolj[$i] = $calc->sub($LUcolj[$i], $s);
                $LUrowi[$j] = $LUcolj[$i];
            }
            // Find pivot and exchange if necessary.
            $p = $j;
            for ($i = $j + 1; $i < $m; $i++) {
                if ($comp->gt($LUcolj[$i]->abs(), $LUcolj[$p]->abs())) {
                    $p = $i;
                }
            }
            if ($p != $j) {
                for ($k = 0; $k < $n; $k++) {
                    //swap
                    $t = $LU[$p][$k];
                    $LU[$p][$k] = $LU[$j][$k];
                    $LU[$j][$k] = $t;
                }
                $k = $this->piv[$p];
                $this->piv[$p] = $this->piv[$j];
                $this->piv[$j] = $k;
                $this->pivsign = $this->pivsign * -1;
            }
            // Compute multipliers.
            if (($j < $m) && $comp->neq($LU[$j][$j], $zeroInt)) {
                for ($i = $j + 1; $i < $m; $i++) {
                    $LU[$i][$j] = $calc->div($LU[$i][$j],$LU[$j][$j]);
                }
            }
        }

        $this->set('LU', $this->createCorrectMatrixType($mA, $LU));
    }

    /**
     * Set other products of the decomposition
     */
    protected function setOtherProducts(NumericMatrix $mA)
    {
        $this->setLowerProduct($mA);
        $this->setUpperProduct($mA);
        $this->setPivotVector($mA);
        $this->setPermutationMatrix($mA);
        $this->setDeterminant($mA);
    }

    /**
     * Set lower triangular factor.
     */
    protected function setLowerProduct(NumericMatrix $mA)
    {
        $m = $this->LU->rows();
        $n = $this->LU->columns();
        $LU = $this->LU->toArray();
        $rcFactor = $this->rows -$this->cols;
        //set Lower
        $this->set(
                'L',
                function() use ($m, $n, $LU, $rcFactor, $mA) {
                    $L = [];
                    for ($i = 0; $i < $m; $i++) {
                        for ($j = 0; $j < $n; $j++) {
                            if ($i > $j) {
                                $L[$i][$j] = $LU[$i][$j];
                            } elseif ($i == $j) {
                                $L[$i][$j] = $this->createCorrectScalarType($mA, 1);
                            } else {
                                $L[$i][$j] = $this->createCorrectScalarType($mA, 0);
                            }
                        }
                    }

                    //remove extra cols for non square matrices
                    if ($rcFactor < 0) {
                        $mLL = new NumericMatrix($L);
                        return $this->createCorrectMatrixType(
                                $mA,
                                $mLL('Colslice', array(1,$mLL->columns()+$rcFactor))->toArray());
                    } else {
                        return $this->createCorrectMatrixType($mA, $L);
                    }
                }
        );
    }

    /**
     * Set upper triangular factor.
     */
    protected function setUpperProduct(NumericMatrix $mA)
    {
        $n = $this->LU->columns();
        $LU = $this->LU->toArray();
        $rcFactor = $this->cols -$this->rows;
        $this->set(
                'U',
                function() use ($n, $LU, $rcFactor, $mA) {
                    $U = [];
                    for ($i = 0; $i < $n; $i++) {
                        for ($j = 0; $j < $n; $j++) {
                            if ($i <= $j) {
                                $U[$i][$j] = (isset($LU[$i][$j]) ? $LU[$i][$j] : $this->createCorrectScalarType($mA, 0));
                            } else {
                                $U[$i][$j] = $this->createCorrectScalarType($mA, 0);
                            }
                        }
                    }
                    //remove extra rows for non square matrices
                    if ($rcFactor > 0) {
                        $mUU = new NumericMatrix($U);
                        return $this->createCorrectMatrixType(
                                $mA,
                                $mUU('Rowslice', array(1,$mUU->rows()-$rcFactor))->toArray());
                    } else {
                        return $this->createCorrectMatrixType($mA, $U);
                    }
                }
        );
    }

    /**
     * Set pivot permutation vector.
     */
    protected function setPivotVector(NumericMatrix $mA)
    {
        $mB = $this->createCorrectMatrixType($mA, [$this->piv]);
        $this->set(
                'PivotVector',
                $mB("Add\\Scalar", 1));
    }

    /**
     * Set permutation matrix
     */
    protected function setPermutationMatrix(NumericMatrix $mA)
    {
        $p = $this->piv;
        $this->set(
                'PermutationMatrix',
                function() use ($p, $mA) {
                    $size = count($p);
                    $perm = array_fill(0, $size, array_fill(0, $size, 0));
                    for ($j=0; $j<$size; $j++) {
                        $perm[array_shift($p)][$j] = 1;
                    }
                    return $this->createCorrectMatrixType($mA,$perm);
                }
                );
    }

    /**
     * Set determinant of original matrix if it is square
     */
    protected function setDeterminant(NumericMatrix $mA)
    {
        if (!$mA->is('square')) {
            //determinant undefined for non square matrix
            $this->set('Det', null);
            return;
        }

        if ($mA->is('empty')) {
            $this->set('Det', $this->createCorrectScalarType($mA, 1));
            return;
        }

        $det = $this->createCorrectScalarType($mA, $this->pivsign);
        $LU = $this->LU->toArray();
        $calc = new Calculator();

        $this->set(
                'Det',
                function() use ($det, $LU, $calc) {
                    $c = count($LU);
                    for ($j = 0; $j < $c; $j++) {
                        $det = $calc->mul($det, $LU[$j][$j]);
                    }

                    return $det;
                }
                );
    }
}

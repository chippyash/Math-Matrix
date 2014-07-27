<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Transformation\Decomposition;

use chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition;
use chippyash\Math\Matrix\RationalMatrix;

/**
 * This is lifted from the JAMA package and adapted for this library from its
 * PHP4 origin into PHP 5 and to fit the chippyash\Matrix model
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

    /**
     * Products of the decomposition
     * @var array [productName => Matrix,...]
     */
    protected $products = array(
        'LU' => null, //Matrix: full decomposition
        'L' => null,  //Matrix: lower triangle
        'U' => null,   //Matrix: upper triangle
        'PivotVector' => null, //Matrix: Pivot vector of the decomposition,
        'PermutationMatrix' => null, //Matrix: permutation matrix of decomposition,
        'Det' => null //numeric: determinant of source matrix if matrix is square else null
    );

    /**
     * Internal storage of pivot vector.
     * @var array
     */
    protected $piv = array();

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
     * @param \chippyash\Matrix\Matrix $mA
     * @param mixed $extra ignored
     * @return \chippyash\Matrix\Transformation\Decomposition\Lu
     */
    protected function decompose(Matrix $mA, $extra = null)
    {
        $this->LUDecomposition($mA);

        $this->setOtherProducts();

        return $this;
    }

    /**
     * LU Decomposition constructor.
     * @param $A Rectangular matrix
     * @return Structure to access L, U and piv.
     */
    protected function LUDecomposition(Matrix $mA)
    {
        // Use a "left-looking", dot-product, Crout/Doolittle algorithm.
        $LU = $mA->toArray();
        $m = $this->rows = $mA->rows();
        $n = $this->cols = $mA->columns();
        for ($i = 0; $i < $m; $i++) {
            $this->piv[$i] = $i;
        }
        $this->pivsign = 1;
        $LUrowi = array();
        $LUcolj = array();
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
                $s = 0;
                for ($k = 0; $k < $kmax; $k++) {
                    $s += $LUrowi[$k] * $LUcolj[$k];
                }
                $LUcolj[$i] -= $s;
                $LUrowi[$j] = $LUcolj[$i];
            }
            // Find pivot and exchange if necessary.
            $p = $j;
            for ($i = $j + 1; $i < $m; $i++) {
                if (abs($LUcolj[$i]) > abs($LUcolj[$p])) {
                    $p = $i;
                }
            }
            if ($p != $j) {
                for ($k = 0; $k < $n; $k++) {
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
            if (($j < $m) && ( $LU[$j][$j] != 0)) {
                for ($i = $j + 1; $i < $m; $i++) {
                    $LU[$i][$j] /= $LU[$j][$j];
                }
            }
        }

        $this->set('LU', new Matrix($LU));
    }

    /**
     * Set other products of the decomposition
     */
    protected function setOtherProducts()
    {
        $this->setLowerProduct();
        $this->setUpperProduct();
        $this->setPivotVector();
        $this->setPermutationMatrix();
        $this->setDeterminant();
    }

    /**
     * Set lower triangular factor.
     */
    protected function setLowerProduct()
    {
        $m = $this->LU->rows();
        $n = $this->LU->columns();
        $LU = $this->LU->toArray();
        $rcFactor = $this->rows -$this->cols;
        //set Lower
        $this->set(
                'L',
                function() use ($m, $n, $LU, $rcFactor) {
                    $L = array();
                    for ($i = 0; $i < $m; $i++) {
                        for ($j = 0; $j < $n; $j++) {
                            if ($i > $j) {
                                $L[$i][$j] = $LU[$i][$j];
                            } elseif ($i == $j) {
                                $L[$i][$j] = 1;
                            } else {
                                $L[$i][$j] = 0;
                            }
                        }
                    }
                    $mLL = new Matrix($L);
                    //remove extra cols for non square matrices
                    if ($rcFactor < 0) {
                        return $mLL('Colslice', array(1,$mLL->columns()+$rcFactor));
                    } else {
                        return $mLL;
                    }
                }
        );
    }

    /**
     * Set upper triangular factor.
     */
    protected function setUpperProduct()
    {
        $n = $this->LU->columns();
        $LU = $this->LU->toArray();
        $rcFactor = $this->cols -$this->rows;
        $this->set(
                'U',
                function() use ($n, $LU, $rcFactor) {
                    $U = array();
                    for ($i = 0; $i < $n; $i++) {
                        for ($j = 0; $j < $n; $j++) {
                            if ($i <= $j)
                                $U[$i][$j] = (isset($LU[$i][$j]) ? $LU[$i][$j] : 0);
                            else
                                $U[$i][$j] = 0;
                        }
                    }
                    $mUU = new Matrix($U);
                    //remove extra rows for non square matrices
                    if ($rcFactor > 0) {
                        return $mUU('Rowslice', array(1,$mUU->rows()-$rcFactor));
                    } else {
                        return $mUU;
                    }
                }
        );
    }

    /**
     * Set pivot permutation vector.
     * @return Matrix Pivot vector
     */
    protected function setPivotVector()
    {
        $mA = new Matrix(array($this->piv));
        $this->set(
                'PivotVector',
                $mA("Add\\Scalar", 1));
    }

    /**
     * Set permutation matrix
     * @return Matrix
     */
    protected function setPermutationMatrix()
    {
        $p = $this->piv;
        $this->set(
                'PermutationMatrix',
                function() use ($p) {
                    $size = count($p);
                    $perm = array_fill(0, $size, array_fill(0, $size, 0));
                    for ($j=0; $j<$size; $j++) {
                        $perm[array_shift($p)][$j] = 1;
                    }
                    return new Matrix($perm);
                }
                );
    }

    /**
     * Set determinant or original matrix if it is square
     * @return numeric
     */
    protected function setDeterminant()
    {
        if ($this->LU->columns() != $this->LU->rows()) {
            //determinant undefined for non square matrix
            return;
        }

        $pivsign = $this->pivsign;
        $LU = $this->LU->toArray();

        $this->set(
                'Det',
                function() use ($pivsign, $LU) {
                    $d = $pivsign;
                    for ($j = 0; $j < count($LU); $j++) {
                        $d *= $LU[$j][$j];
                    }
                    return $d;
                }
                );
    }
}

<?php

/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Derivative\Strategy\Determinant;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\Interfaces\DeterminantStrategyInterface;
use chippyash\Matrix\Transformation\Cofactor;
use chippyash\Matrix\Matrix;
use chippyash\Math\Type\Calculator;
use chippyash\Type\Number\Complex\ComplexType;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\IntType;

/**
 * Determinant strategy for matrix inversion
 *
 */
class Internal implements DeterminantStrategyInterface
{

    /**
     * Cofactor function
     * @var chippyash\Matrix\Transformation\Cofactor
     */
    protected $fCof;

    /**
     * Calculator
     * @var chippyash\Math\Type\Calculator
     */
    protected $calc;
    
    /**
     * Are we dealing with a complex matrix?
     * @var boolean
     */
    protected $isComplex = false;
    
    /**
     * Are we dealing with a rational matrix?
     * @var boolean
     */
    protected $isRational = false;
    
    /**
     * Compute determinant using internal method
     * $mA must be
     * - square
     * This is not checked here - that is done in the determinant derivative class
     *
     * @param NumericMatrix $mA
     * @return chippyash\Type\Number\NumericTypeInterface
     */
    public function determinant(NumericMatrix $mA)
    {
        $this->isComplex = ($mA instanceof ComplexMatrix);
        $this->isRational = ($mA instanceof RationalMatrix);
        
        return $this->det($mA);
    }

    /**
     * Recursive determinant function
     * 
     * @param \chippyash\Matrix\Matrix $mA
     * @return chippyash\Type\Number\NumericTypeInterface
     */
    protected function det(Matrix $mA)
    {
        $rowCount = $mA->rows();
        if ($rowCount == 2) { //2X2 matrix
            return $this->det2($mA);
        }

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
     * @param \chippyash\Matrix\Matrix $mA
     * @return chippyash\Type\Number\NumericTypeInterface
     */
    protected function det2(Matrix $mA)
    {
        $data = $mA->toArray();
        return (
                $this->calc()->sub(
                    $this->calc()->mul($data[0][0], $data[1][1]),
                    $this->calc()->mul($data[0][1], $data[1][0])
                )
        );
    }

    /**
     * Get determinant for 3 X 3 matrix
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return chippyash\Type\Number\NumericTypeInterface
     */
    protected function det3(Matrix $mA)
    {
        if ($this->isComplex) {
            $det = new ComplexType(new FloatType(0), new FloatType(0));
        } elseif ($this->isRational) {
            $det = new RationalType(new IntType(0), new InType(1));
        } else {
            $det = new FloatType(0);
        }
        $positive = true;
        $c = $this->calc();
        for ($r = 1; $r < 4; $r++) {
            $t = $c->mul(
                    $mA->get($r, 1), 
                    $this->det2($this->cof()->transform($mA,array($r, 1)))
                    );
            $det = $c->add(
                    $det, ($positive ? $t : $t->negate())
                    );
            $positive = !$positive;
        }

        return $det;
    }

    /**
     * Get determinant for arbitrarily large matrices
     *
     * @link http://www.intmath.com/matrices-determinants/2-large-determinants.php
     * @param \chippyash\Matrix\Matrix $mA
     * @return chippyash\Type\Number\NumericTypeInterface
     */
    protected function detN(Matrix $mA)
    {
        if ($this->isComplex) {
            $det = new ComplexType(new FloatType(0), new FloatType(0));
        } elseif ($this->isRational) {
            $det = new RationalType(new IntType(0), new InType(1));
        } else {
            $det = new FloatType(0);
        }
        $positive = true;
        $rowLimit = $mA->rows() + 1;
        for ($r = 1; $r < $rowLimit; $r ++) {
            $t = $this->calc()->mul(
                    $mA->get($r, 1),
                    $this->det($this->cof()->transform($mA,array($r, 1)))
                    );
            $det = $this->calc()->add(
                    $det, ($positive ? $t : $t->negate())
                    );
            $positive = !$positive;
        }
        return $det;
    }

    /**
     * @return chippyash\Matrix\Transformation\Cofactor
     */
    protected function cof()
    {
        if (empty($this->fCof)) {
            $this->fCof = new Cofactor();
        }
        
        return $this->fCof;
    }
    
    /**
     * @return chippyash\Math\Type\Calculator
     */
    protected function calc()
    {
        if (empty($this->calc)) {
            $this->calc = new Calculator();
        }
        
        return $this->calc;
    }
}

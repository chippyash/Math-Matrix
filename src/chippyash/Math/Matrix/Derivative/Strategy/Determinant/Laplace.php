<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)#Determinant
 * @link http://en.wikipedia.org/wiki/Laplace_expansion
 */

namespace chippyash\Math\Matrix\Derivative\Strategy\Determinant;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Interfaces\DeterminantStrategyInterface;
use chippyash\Matrix\Transformation\Cofactor;
use chippyash\Math\Type\Calculator;
use chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use chippyash\Math\Matrix\Interfaces\TuningInterface;

/**
 * Laplace expansion strategy for matrix determinant
 * Computing up to about M[9] is about as much as you can
 * do with this method of computing determinants in a reasonable time.
 *
 * @link examples/example-laplace-bounds.php
 *
 */
class Laplace implements DeterminantStrategyInterface, TuningInterface
{
    use CreateCorrectScalarType;

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
     * Cache for part processed determinants
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Compute determinant using laplace expansion
     * $mA must be
     * - square
     * This is not checked here - that is done in the determinant derivative class
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $mA
     * @return \chippyash\Type\Number\NumericTypeInterface
     */
    public function determinant(NumericMatrix $mA)
    {
        return $this->det($mA);
    }

    /**
     * Tune an item on a class. Available items:
     * - clearCache : boolean - if $value == true, will clear determinant cache
     *                Always returns number of items currently in cache
     *
     * @param \chippyash\Type\String\StringType $name Item to tune
     * @param mixed $value Value to set
     *
     * @return mixed - previous value of item
     *
     * @throws \InvalidArgumentException if name does not exist
     */
    public function tune(\chippyash\Type\String\StringType $name, $value)
    {
        if ($name() !== 'clearCache') {
            throw new \InvalidArgumentException("{$name} is unknown for tuning");
        }

        $ret = count(self::$cache);
        if ($value) {
            self::$cache = [];
        }
        return $ret;
    }

    /**
     * Recursive determinant function
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $mA
     * @return \chippyash\Type\Number\NumericTypeInterface
     */
    protected function det(NumericMatrix $mA)
    {
        //echo $mA->setFormatter(new \chippyash\Matrix\Formatter\Ascii)->display();
        $rowCount = $mA->rows();

        if ($rowCount == 0) {
            return $this->createCorrectScalarType($mA, 1);
        }

        if ($rowCount == 1) {
            return $mA->get(1,1);
        }

        $possAnswer = $this->checkInCache($mA);
        if ($possAnswer !== false) {
            return $possAnswer;
        }

        if ($rowCount == 2) {
            //2X2 matrix
            $det = $this->det2($mA);
        } else {
            //nXn matrix
            $det = $this->detN($mA);
        }
        $this->storeInCache($mA, $det);

        return $det;
    }

    /**
     * Return determinant of a 2X2 matrix
     * @link http://en.wikipedia.org/wiki/Matrix_determinant#2.C2.A0.C3.97.C2.A02_matrices
     * [[a, b]
     *  [c, d]]
     * ad - bc
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $mA
     */
    protected function det2(NumericMatrix $mA)
    {
        $c = $this->calc();
        return $c->sub(
                    $c->mul($mA->get(1,1), $mA->get(2,2)),
                    $c->mul($mA->get(1,2), $mA->get(2,1))
                );
    }

    /**
     * Get determinant for arbitrarily large matrices
     *
     * @link http://www.intmath.com/matrices-determinants/2-large-determinants.php
     * @param \chippyash\Math\Matrix\NumericMatrix $mA
     * @return chippyash\Type\Number\NumericTypeInterface
     */
    protected function detN(NumericMatrix $mA)
    {

        $det = $this->createCorrectScalarType($mA, 0);
        $positive = true;
        $rowLimit = $mA->rows() + 1;
        $c = $this->calc();
        for ($r = 1; $r < $rowLimit; $r ++) {
            $t = $c->mul(
                    $mA->get($r, 1),
                    $this->det($this->cof()->transform($mA,array($r, 1)))
                    );
            $det = $c->add(
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

    /**
     * Check for matrix determinant answer in cache
     *
     * @param NumericMatrix $mA
     * @return boolean|numeric
     */
    protected function checkInCache($mA)
    {
        $key = md5(serialize($mA));
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        return false;
    }

    /**
     * Store matrix determinant answer in cache
     *
     * @param NumericMatrix $mA
     * @param numeric $answer
     */
    protected function storeInCache($mA, $answer)
    {
        $key = md5(serialize($mA));
        self::$cache[$key] = $answer;
    }
}

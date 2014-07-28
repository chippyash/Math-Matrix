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

use chippyash\Math\Matrix\Interfaces\DecompositionInterface;
use chippyash\Matrix\Transformation\AbstractTransformation;
use chippyash\Matrix\Matrix;
use chippyash\Matrix\Traits\AssertMatrixIsNotEmpty;
use chippyash\Matrix\Interfaces\InvokableInterface;
use chippyash\Math\Matrix\NumericMatrix;

/**
 * Base abstract for decomposition
 *
 * Has invokable interface
 */
abstract class AbstractDecomposition extends AbstractTransformation
                                     implements DecompositionInterface
{
    use AssertMatrixIsNotEmpty;

    /**
     * Products of the decomposition
     * These can be callable functions
     *
     * @var array [productName => mixed,...]
     */
    protected $products = array();

    /**
     * Run the decomposition transformation after checking that matrix is not empty
     *
     * @see DecompositionInterface::transform
     * @throws chippyash\Matrix\Exceptions\ComputationExceptio
     * @return chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition Fluent Interface
     */
    public function transform(Matrix $mA, $extra = null)
    {
        $this->assertMatrixIsNotEmpty($mA)
             ->decompose($mA);

        return $this;
    }

    /**
     * Get a product from the decomposition
     *
     * @param string $productName
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function product($productName)
    {
        if (!array_key_exists($productName, $this->products)) {
            throw new \InvalidArgumentException($productName);
        }

        if (is_callable($this->products[$productName])) {
            $func = $this->products[$productName];
            if (is_object($func) && $func instanceof InvokableInterface ) {
                //i.e. it's an invokable class so return the class not the invokation
                return $func;
            }
            $ret = $func();
            return $ret;
        }

        return $this->products[$productName];
    }

    /**
     * Magic Get
     * Proxies to get()
     * Allows you to call $decomposition->productName
     *
     * @param string $productName
     * @return mixed
     */
    public function __get($productName)
    {
        return $this->product($productName);
    }

    /**
     * Do the actual decomposition
     *
     * @param NumericMatrix $mA
     * @return void
     */
    abstract protected function decompose(NumericMatrix $mA);

    /**
     * Set a product for this decomposition
     *
     * @param string $productName
     * @param mixed $value
     * @return \chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition Fluent Interface
     * @throws \InvalidArgumentException
     */
    protected function set($productName, $value)
    {
        if (!array_key_exists($productName, $this->products)) {
            throw new \InvalidArgumentException($productName);
        }
        $this->products[$productName] = $value;

        return $this;
    }
}

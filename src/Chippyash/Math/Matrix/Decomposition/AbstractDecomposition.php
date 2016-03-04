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

use Chippyash\Math\Matrix\Interfaces\DecompositionInterface;
use Chippyash\Matrix\Interfaces\InvokableInterface;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;
use Chippyash\Type\Interfaces\NumericTypeInterface;

/**
 * Base abstract for decomposition
 *
 * Has invokable interface
 */
abstract class AbstractDecomposition implements DecompositionInterface
{

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
     * @see DecompositionInterface::decompose
     * @throws Chippyash\Matrix\Exceptions\ComputationExceptio
     * @return Chippyash\Matrix\Decomposition\AbstractDecomposition Fluent Interface
     */
    abstract public function decompose(NumericMatrix $mA, $extra = null);

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
            if (is_object($func) && ($func instanceof InvokableInterface || $func instanceof NumericTypeInterface)) {
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
     * Proxy to decompose()
     * Allows object to be called as function
     *
     * @param NumericMatrix $mA
     * @param mixed $extra Additional input required for decomposition
     *
     * @return numeric
     *
     * @throws Chippyash/Matrix/Exceptions/ComputationException
     */
    public function __invoke()
    {
        $numArgs = func_num_args();
        if ($numArgs == 1) {
            return $this->decompose(func_get_arg(0));
        } elseif($numArgs == 2) {
            return $this->decompose(func_get_arg(0), func_get_arg(1));
        } else {
            throw new ComputationException('Invoke method expects 0<n<3 arguments');
        }
    }

    /**
     * Set a product for this decomposition
     *
     * @param string $productName
     * @param mixed $value
     * @return \Chippyash\Matrix\Decomposition\AbstractDecomposition Fluent Interface
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

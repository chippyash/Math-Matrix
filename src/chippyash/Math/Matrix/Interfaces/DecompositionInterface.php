<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Interfaces;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Matrix\Interfaces\InvokableInterface;

/**
 * Interface for a Matrix decomposition
 * Decompositions return n>1 products so we need additional support to access
 * those products.
 *
 * @codeCoverageIgnore
 */
interface DecompositionInterface extends InvokableInterface
{
    /**
     * Decompose a numeric matrix
     *
     * @param chippyash\Math\Matrix\NumericMatrix $mA matrix to decompose
     * @param mixed $extra Additional information required for the decomposition
     *
     * @throws chippyash\Matrix\Exceptions\ComputationException
     * @return chippyash\Math\Matrix\Interfaces\DecompositionInterface Fluent Interface
     */
    public function decompose(NumericMatrix $mA, $extra = null);

    /**
     * Get a decomposition product
     * The nature of the product depends on the decomposition
     *
     * @param string $productName
     * @return mixed
     * @codeCoverageIgnore
     */
    public function product($productName);
}

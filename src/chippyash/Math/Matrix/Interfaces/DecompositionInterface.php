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

use chippyash\Matrix\Interfaces\TransformationInterface;

/**
 * Interface for a Matrix decomposition
 * Decompositions return n>1 products so we need additional support to access
 * those products.  By default the transform function should return the decomposition object,
 * with the client calling ->product() to retrieve the various products of the decomposition
 *
 * @codeCoverageIgnore
 */
interface DecompositionInterface extends TransformationInterface
{
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

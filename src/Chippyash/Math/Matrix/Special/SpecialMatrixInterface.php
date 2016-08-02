<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Special;

use Chippyash\Math\Matrix\NumericMatrix;

interface SpecialMatrixInterface
{
    /**
     * @param array $args
     * @return NumericMatrix
     */
    public function create(array $args);
}
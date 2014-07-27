<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Exceptions;

use chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * Computation for the matrix operand types is undefined
 */
class UndefinedComputationException extends ComputationException
{
    protected $undefTpl = "Undefined computation: %s";

    public function __construct($reason, $code = -1, $previous = null)
    {
        $message = sprintf($this->undefTpl, $reason);
        parent::__construct($message, $code, $previous);
    }
}

<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Exceptions;

use Chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * No inverse can be found for a matrix
 */
class NoInverseException extends ComputationException
{
    protected $undefTpl = "No inverse: %s";

    public function __construct($reason, $code = -1, $previous = null)
    {
        $message = sprintf($this->undefTpl, $reason);
        parent::__construct($message, $code, $previous);
    }
}

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
use chippyash\Math\Matrix\Exceptions\MathMatrixException;

/**
 * Matrix is singular
 */
class SingularMatrixException extends MathMatrixException
{
    protected $msgTpl = "Matrix is singular: %s";

    public function __construct($extra, $code = -1, $previous = null)
    {
        $message = sprintf($this->msgTpl, $extra);
        parent::__construct($message, $code, $previous);
    }
}

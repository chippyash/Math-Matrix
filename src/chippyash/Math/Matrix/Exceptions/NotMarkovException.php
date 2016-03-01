<?php
/**
 * Markov
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Math\Matrix\Exceptions;


class NotMarkovException extends MathMatrixException
{
    protected $msgTpl = "Matrix is not suitable for a Markov Chain: ";

    public function __construct($reason = '', $code = -1, $previous = null)
    {
        parent::__construct($this->msgTpl . $reason, $code, $previous);
    }
}
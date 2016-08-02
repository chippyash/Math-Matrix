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
use Chippyash\Type\String\StringType;
use Chippyash\Validation\Common\Lambda;
use Chippyash\Validation\Exceptions\InvalidParameterException;
use Chippyash\Validation\Logical\LAnd;
use Chippyash\Validation\Logical\LOr;
use Chippyash\Validation\Pattern\HasTypeMap;

/**
 * Zeroes Matrix
 * Create a matrix or vector filled with 0
 *
 * Expects 1 parameter: rows and second optional parameter, cols
 * cols defaults to 1 if not specified.
 */
class Zeros extends AbstractSpecial
{
    const ERR1 = 'rows parameter must be integer > 0';
    const ERR2 = 'rows and cols parameter must be integer > 0';

    /**
     * Map of argument names
     * @var array
     */
    protected $map = ['rows', 'cols'];
    
    /**
     * @inheritDoc
     */
    protected function validateArguments(array $args)
    {
        $valA1 = new HasTypeMap([
                'rows' => 'integer'
            ]
        );
        $valA2 = new Lambda(function($args) {
            return $args['rows'] > 0 && !array_key_exists('cols', $args);
        }, new StringType(self::ERR1));
        $valA = new LAnd($valA1, $valA2);

        $valB1 = new HasTypeMap([
                'rows' => 'integer',
                'cols' => 'integer'
            ]
        );
        $valB2 = new Lambda(function($args) {
            return $args['rows'] > 0 && $args['cols'] > 0;
        },
            new StringType(self::ERR2)
        );
        $valB = new LAnd($valB1, $valB2);

        $validator = new LOr($valA,$valB);

        if (!$validator->isValid($args)) {
            throw new InvalidParameterException(implode(':', $validator->getMessages()));
        }
    }

    /**
     * @inheritDoc
     */
    protected function createMatrix(array $args)
    {
        $rows = $args['rows'];
        $cols = array_key_exists('cols', $args) ? $args['cols'] : 1;

        return $this->zeroes($rows, $cols);
    }

    private function zeroes($rows, $cols)
    {
        $ret = array_fill(0, $rows, array_fill(0, $cols, 0));
        return new NumericMatrix($ret);
    }
}